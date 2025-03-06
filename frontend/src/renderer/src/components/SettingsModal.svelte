<script lang="ts">
  import {
    Badge,
    Button,
    Indicator,
    Input,
    Label,
    Modal,
    Popover,
    Select,
    Spinner,
    Toggle,
  } from "flowbite-svelte";
  import { onMount } from "svelte";
  import type { AppInfoModel } from "../models/appInfo.model";
  import type { ErrorModel } from "../models/error.model";
  import type { SchoolModel } from "../models/school.model";
  import { retrieveSchoolList } from "../services/api.service";
  import { getHash } from "../services/core.service";
  import { serverStatus } from "../stores/connection.store";
  import { core, resetCoreStore } from "../stores/core.store";
  import ErrorCard from "./ErrorCard.svelte";

  export let open = false;

  let schools: SchoolModel[] = [];
  let selectedSchool: number | null = null;
  let adminPassword: string = "";
  let weekView: boolean = false;
  let numPadControl: boolean = false;
  let isLoading = true;
  let isSaving = false;
  let isSaved = false;
  let error: ErrorModel | null = null;
  let showResetModal = false;
  let appInfo: AppInfoModel;
  let ServerUrl: string | null = null;
  let logoutTimeOut: number = 20;
  let autoLogout: boolean = false;
  let autoLaunchEnabled = false;
  let barcodeScanner: boolean = false;
  let showStartTimeLesson: boolean = false;
  let newPassword: string = "";
  let confirmPassword: string = "";
  let passwordMismatch: boolean = false;
  let hashedPassword: string | null = null;

  $: passwordMismatch = newPassword !== confirmPassword;

  /**
   * Initializes the settings menu component on mount.
   *
   * This function:
   * 1. Retrieves app information from the API
   * 2. Fetches the list of available schools
   * 3. Validates if the currently selected school exists in the retrieved list
   * 4. Falls back to a placeholder school entry if school retrieval fails
   * 5. Loads user preferences from the core store including:
   *    - Selected school
   *    - Week view setting
   *    - Numpad control setting
   *    - Server URL
   *    - Auto logout settings
   * 6. Checks auto-launch status
   *
   * @throws {ErrorModel} When school list retrieval fails
   */
  onMount(async () => {
    appInfo = await window.api.appInfo();

    // Retrieve the school list
    try {
      schools = await retrieveSchoolList();
      // Double check the selectedSchool is available in the retrieved schools
      if (
        selectedSchool &&
        !schools.some((s) => s.schoolId === selectedSchool)
      ) {
        error = {
          message: "Geselecteerde school niet gevonden",
          details: "De opgeslagen school bestaat niet meer in de database",
        };
        selectedSchool = null;
      }
    } catch (err) {
      if ($core.schoolId && $core.schoolInYearId) {
        schools = [
          {
            schoolId: $core.schoolId,
            schoolInSchoolYearId: $core.schoolInYearId,
            schoolYear: 0,
            schoolName: "Onbekende school (id " + $core.schoolId + ")",
            projectName: "project " + $core.schoolInYearId,
          },
        ];
      }
      error = err as ErrorModel;
    } finally {
      isLoading = false;
    }

    // Load core store values
    const coreValues = $core;
    selectedSchool = coreValues.schoolId;
    weekView = coreValues.weekView;
    numPadControl = coreValues.numPadControl;
    ServerUrl = coreValues.serverUrl;
    autoLogout = coreValues.autoLogout;
    logoutTimeOut = coreValues.logoutTimeOut;
    autoLaunchEnabled = await window.api.getAutoLaunchStatus();
    barcodeScanner = coreValues.barcodeScanner;
    showStartTimeLesson = coreValues.showStartTimeLesson;
  });

  /**
   * Saves the user settings to the core state.
   *
   * This async function handles saving various settings including:
   * - School selection
   * - Admin password (hashed)
   * - Week view preference
   * - Numpad control settings
   * - Auto logout settings and timeout
   *
   * @throws {ErrorModel} If:
   * - No school is selected
   * - Admin password is less than 4 characters
   * - Logout timeout is less than 5 seconds
   *
   * @effects
   * - Sets isSaving flag during operation
   * - Updates core state with new settings
   * - Clears admin password after saving
   * - Shows success state for 3 seconds via isSaved flag
   * - Sets error state if validation fails
   */
  async function saveSettings() {
    isSaving = true;
    error = null;
    const selectedSchoolData = schools.find(
      (s) => s.schoolId === selectedSchool,
    );
    try {
      // Check user input
      if (!selectedSchoolData) {
        throw { message: "Selecteer een school" };
      }

      // If user is trying to change password
      if (newPassword || confirmPassword) {
        // Check if passwords match
        if (newPassword !== confirmPassword) {
          throw {
            message: "Wachtwoorden komen niet overeen",
          };
        }

        // Check minimum length
        if (newPassword.length < 4) {
          throw {
            message: "Beheerderscode moet minimaal 4 tekens lang zijn",
          };
        }

        // Hash the new password
        hashedPassword = await getHash(newPassword);
        newPassword = "";
        confirmPassword = "";
      }

      if (logoutTimeOut && logoutTimeOut < 5) {
        throw {
          message: "Automatisch uitloggen moet minimaal 5 seconde zijn",
        };
      }

      // Update store
      core.update((state) => ({
        ...state,
        schoolId: selectedSchoolData.schoolId,
        schoolInYearId: selectedSchoolData.schoolInSchoolYearId,
        adminPassword: hashedPassword || state.adminPassword,
        weekView: weekView,
        numPadControl: numPadControl,
        autoLogout: autoLogout,
        logoutTimeOut: logoutTimeOut,
        barcodeScanner: barcodeScanner,
        showStartTimeLesson: showStartTimeLesson,
      }));

      // Toggle auto-launch setting
      await window.api.setAutoLaunch(autoLaunchEnabled);

      adminPassword = "";
      isSaving = false;
      isSaved = true;
      setTimeout(() => (isSaved = false), 3000);
    } catch (err) {
      error = err as ErrorModel;
    } finally {
      isSaving = false;
    }
  }

  /**
   * Handles the confirmation of resetting the application state.
   * Performs the following actions:
   * 1. Resets the core store to its initial state
   * 2. Closes the reset confirmation modal
   * 3. Sets loading state to true
   * 4. Reloads the application after a 1 second delay
   */
  function confirmReset() {
    resetCoreStore();
    showResetModal = false;
    isLoading = true;
    setTimeout(() => {
      window.location.reload();
    }, 1000);
  }
</script>

<Modal size="lg" title="Instellingen" bind:open>
  <div class="p-4">
    {#if isLoading}
      <div class="flex justify-center items-center p-8">
        <Spinner size="8" />
      </div>
    {:else}
      <form class="space-y-6">
        <div class="space-y-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Algemeen</h3>
          <!-- School Selection -->
          <div class="border rounded-md p-3">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-medium">School</p>
                <p class="text-sm text-gray-500 mt-1">
                    Kies de school of locatie waarvoor je deze applicatie wilt gebruiken
                </p>
              </div>
              <Label class="space-y-2">
                <Select bind:value={selectedSchool} required>
                  <option value={null}>Selecteer een school</option>
                  {#each schools as school}
                    <option value={school.schoolId}>
                      {school.schoolName} ({school.projectName})
                    </option>
                  {/each}
                </Select>
              </Label>
            </div>
          </div>

          <!-- Admin Password Change -->
          <div class="border rounded-md p-3">
            <p class="font-medium">Beheerderswachtwoord</p>
            <div class="space-y-2 mt-2">
              <!-- New password -->
              <Label class="space-y-1">
                <span class="text-sm">Nieuw wachtwoord</span>
                <Input
                  type="password"
                  bind:value={newPassword}
                  placeholder="Vul nieuw wachtwoord in"
                  minlength={4}
                  class={passwordMismatch ? "border-red-500" : ""}
                />
              </Label>

              <!-- Confirm new password -->
              <Label class="space-y-1">
                <span class="text-sm">Bevestig nieuw wachtwoord</span>
                <Input
                  type="password"
                  bind:value={confirmPassword}
                  placeholder="Bevestig nieuw wachtwoord"
                  minlength={4}
                  class={passwordMismatch ? "border-red-500" : ""}
                />
              </Label>

              <!-- Error message for password mismatch -->
              {#if passwordMismatch}
                <p class="text-sm text-red-500">
                  Wachtwoorden komen niet overeen
                </p>
              {/if}

              <!-- Only show when actively changing password -->
              {#if newPassword || confirmPassword}
                <p class="text-xs text-gray-500 mt-1">
                  Het wachtwoord moet minimaal 4 tekens bevatten
                </p>
              {/if}
            </div>
          </div>

          <!-- Auto Launch Toggle -->
          <div class="border rounded-md p-3">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-medium">Automatisch opstarten</p>
                <p class="text-sm text-gray-500 mt-1">
                  Start de applicatie automatisch wanneer de computer wordt
                  opgestart
                </p>
              </div>
              <Toggle bind:checked={autoLaunchEnabled} />
            </div>
          </div>
        </div>

        <div class="border-t border-gray-200 pt-4 space-y-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">
            Roosterweergave
          </h3>
          <!-- Week View Toggle -->
          <div class="border rounded-md p-3">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-medium">Weekoverzicht</p>
                <p class="text-sm text-gray-500 mt-1">
                  Toon het volledige weekrooster in plaats van alleen de
                  dagweergave
                </p>
              </div>
              <Toggle bind:checked={weekView} />
            </div>
          </div>

          <!-- Show Start Time Lesson Toggle -->
          <div class="border rounded-md p-3">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-medium">Toon starttijd van de les</p>
                <p class="text-sm text-gray-500 mt-1">
                  Toon de starttijd van de les in de roosterweergave
                </p>
              </div>
              <Toggle bind:checked={showStartTimeLesson} />
            </div>
          </div>

          <!-- Auto Logout Toggle -->
          <div class="border rounded-md p-3">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-medium">Automatisch uitloggen</p>
                <p class="text-sm text-gray-500 mt-1">
                  Log automatisch uit na een periode van inactiviteit
                </p>
              </div>
              <Toggle bind:checked={autoLogout} />
            </div>
          </div>

          <!-- Logout Timeout alleen tonen als autoLogout aan staat -->
          {#if autoLogout}
            <div
              class="border rounded-md p-3 pl-4 border-l-4 border-l-blue-500 bg-blue-50"
            >
              <Label>
                <span>Automatisch uitloggen na (seconden)</span>
                <Input
                  type="number"
                  bind:value={logoutTimeOut}
                  min="5"
                  class="mt-1"
                />
              </Label>
            </div>
          {/if}
        </div>

        <div class="border-t border-gray-200 pt-4 space-y-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Bediening</h3>

          <!-- NumPad Control Toggle -->
          <div class="border rounded-md p-3">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-medium">NumPad besturing</p>
                <p class="text-sm text-gray-500 mt-1">
                  Gebruik het numerieke toetsenbord voor navigatie
                </p>
              </div>
              <Toggle bind:checked={numPadControl} />
            </div>

            {#if numPadControl}
              <div class="mt-3 text-sm text-gray-600 border-t pt-3">
                <p>
                  Je kunt het numerieke toetsenbord gebruiken om door de
                  applicatie te navigeren:
                </p>
                <h4 class="font-medium mt-2">Hoofdscherm:</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >0-9</kbd
                    > Invullen leerlingnummer
                  </div>
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >.</kbd
                    >
                    of
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >Del</kbd
                    > Backspace
                  </div>
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >Enter</kbd
                    > Bevestigen
                  </div>
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >+</kbd
                    > Volledig wissen
                  </div>
                </div>

                <h4 class="font-medium mt-2">Roosterscherm:</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >/</kbd
                    > Terug (vorige dag/week)
                  </div>
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >*</kbd
                    > Vooruit (volgende dag/week)
                  </div>
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >-</kbd
                    > Schakelen tussen dag/week
                  </div>
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >+</kbd
                    > Terug naar hoofdscherm
                  </div>
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >8</kbd
                    > Omhoog
                  </div>
                  <div>
                    <kbd
                      class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs"
                      >2</kbd
                    > Omlaag
                  </div>
                </div>
              </div>
            {/if}
          </div>

          <!-- Barcode scanner -->
          <div class="border rounded-md p-3">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-medium">Barcode scanner</p>
                <p class="text-sm text-gray-500 mt-1">
                  Gebruik een barcode scanner voor het invullen van
                  leerlingnummers
                </p>
              </div>
              <Toggle bind:checked={barcodeScanner} />
            </div>
          </div>
        </div>

        <!-- App versions -->
        <div class="border-t border-gray-200 pt-4 space-y-6">
          <h3 class="text-lg font-semibold text-gray-900">App Informatie</h3>
          <div class="grid grid-cols-1 gap-4 text-sm text-gray-600 mt-2">
            <div class="flex justify-between">
              <span class="font-medium">Server URL:</span>
              <div class="flex gap-2">
                <Badge id="hover" color="none">{ServerUrl || "Onbekend"}</Badge>
                {#if $serverStatus}
                  <Badge color="green" rounded class="px-2.5 py-0.5">
                    <Indicator color="green" size="xs" class="me-1" />Verbonden
                  </Badge>
                {:else}
                  <Badge color="red" rounded class="px-2.5 py-0.5">
                    <Indicator color="red" size="xs" class="me-1" />Niet
                    verbonden
                  </Badge>
                {/if}
              </div>
              <Popover
                class="w-64 text-sm font-light "
                title="Let op!"
                triggeredBy="#hover"
                >Server URL kan alleen worden gewijzigd door de applicatie te
                resetten</Popover
              >
            </div>
            <div class="flex justify-between">
              <span class="font-medium">App versie:</span>
              <div>
                <Badge color="dark"
                  >Mijn Rooster {appInfo?.appVersion || "Onbekend"}</Badge
                >
                <Badge color="dark"
                  >Electron {appInfo?.electronVersion || "Onbekend"}</Badge
                >
                <Badge color="dark"
                  >Node.js {appInfo?.nodeVersion || "Onbekend"}</Badge
                >
                <Badge color="dark"
                  >Chrome {appInfo?.chromeVersion || "Onbekend"}</Badge
                >
              </div>
            </div>
            <div>
              <!-- Reset Application Button -->
              <Button color="red" on:click={() => (showResetModal = true)}>
                Reset Applicatie
              </Button>
            </div>
          </div>
        </div>
      </form>
    {/if}
  </div>
  <svelte:fragment slot="footer">
    <div class="flex w-full justify-end items-center gap-4">
      {#if error}
        <ErrorCard {error} size="sm" />
      {/if}

      {#if isSaved}
        <Badge color="green">Wijzigingen opgeslagen</Badge>
      {/if}

      <!-- Save Button -->
      <Button on:click={saveSettings} disabled={isSaving}>
        {#if isSaving}
          Opslaan...
        {:else}
          Opslaan
        {/if}
      </Button>
    </div>
  </svelte:fragment>
</Modal>
<!-- Reset Confirmation Modal -->
<Modal
  open={showResetModal}
  size="md"
  title="Reset Applicatie"
  dismissable={false}
>
  <div class="p-6">
    <h3 class="text-lg font-semibold text-gray-900">Bevestig reset</h3>
    <p class="mt-2 text-sm text-gray-600">
      Weet je zeker dat je de applicatie wilt resetten? Dit verwijdert alle
      instellingen en je moet opnieuw verbinden.
    </p>
    <div class="mt-4 flex justify-end gap-2">
      <Button color="light" on:click={() => (showResetModal = false)}
        >Annuleren</Button
      >
      <Button color="red" on:click={confirmReset}>Resetten</Button>
    </div>
  </div>
</Modal>
