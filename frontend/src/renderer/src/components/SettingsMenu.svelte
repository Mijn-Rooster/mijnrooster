<script lang="ts">
  import {
    Button,
    Label,
    Input,
    Select,
    Toggle,
    Modal,
    Badge,
    Spinner,
    Popover,
    Indicator,
  } from "flowbite-svelte";
  import { retrieveSchoolList } from "../services/api.service";
  import { core, resetCoreStore } from "../stores/core.store";
  import type { SchoolModel } from "../models/school.model";
  import type { ErrorModel } from "../models/error.model";
  import ErrorCard from "./ErrorCard.svelte";
  import { onMount } from "svelte";
  import { getHash } from "../services/core.service";
  import type { AppInfoModel } from "../models/appInfo.model";
  import { serverStatus } from "../stores/connection.store";

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
  });

  async function saveSettings() {
    isSaving = true;
    error = null;
    const selectedSchoolData = schools.find(
      (s) => s.schoolId === selectedSchool,
    );
    try {
      const hashedPassword = adminPassword
        ? await getHash(adminPassword)
        : null;

      if (!selectedSchoolData) {
        throw { message: "Selecteer een school" };
      }

      if (adminPassword && adminPassword.length < 4) {
        throw {
          message: "Beheerderscode moet minimaal 4 tekens lang zijn",
        };
      }

      if (logoutTimeOut && logoutTimeOut < 5) {
        throw {
          message: "Automatisch uitloggen moet minimaal 5 seconde zijn",
        };
      }

      core.update((state) => ({
        ...state,
        schoolId: selectedSchoolData.schoolId,
        schoolInYearId: selectedSchoolData.schoolInSchoolYearId,
        adminPassword: hashedPassword || state.adminPassword,
        weekView: weekView,
        numPadControl: numPadControl,
        autoLogout: autoLogout,
        logoutTimeOut: logoutTimeOut,
      }));

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

  function confirmReset() {
    resetCoreStore();
    showResetModal = false;
    isLoading = true;
    setTimeout(() => {
      window.location.reload();
    }, 1000);
  }
</script>

{#if isLoading}
  <div class="flex justify-center items-center p-8">
    <Spinner size="8" />
  </div>
{:else}
  <form class="space-y-6">
    <div class="space-y-6">
      <!-- School Selection -->
      <Label class="space-y-2">
        <span>School</span>
        <Select bind:value={selectedSchool} required>
          <option value={null}>Selecteer een school</option>
          {#each schools as school}
            <option value={school.schoolId}>
              {school.schoolName} ({school.projectName})
            </option>
          {/each}
        </Select>
      </Label>

      <!-- Admin Password Change -->
      <Label class="space-y-2">
        <span>Wijzig admin wachtwoord</span>
        <Input
          type="password"
          bind:value={adminPassword}
          placeholder="Nieuw wachtwoord (optioneel)"
          minlength={4}
        />
      </Label>

      <!-- Week View Toggle -->
      <Label class="flex items-center space-x-2">
        <Toggle bind:checked={weekView} />
        <span>Toon weekoverzicht</span>
      </Label>

      <!-- NumPad Control Toggle -->
      <Label class="flex items-center space-x-2">
        <Toggle bind:checked={numPadControl} />
        <span>Gebruik NumPad besturing</span>
      </Label>

      <!-- Auto Logout Toggle -->
      <Label class="flex items-center space-x-2">
        <Toggle bind:checked={autoLogout} />
        <span>Automatisch uitloggen</span>
      </Label>

      <!-- Logout Timeout -->
      <Label>
        <span>Automatisch uitloggen (sec)</span>
        <Input
          type="number"
          bind:value={logoutTimeOut}
          disabled={!autoLogout}
        />
      </Label>
    </div>

    <!-- App versions -->
    <div class="border-t border-gray-200 pt-4">
      <h3 class="text-lg font-semibold text-gray-900">App Informatie</h3>
      <div class="grid grid-cols-1 gap-4 text-sm text-gray-600 mt-2">
        <div class="flex justify-between">
          <span class="font-medium">Server URL:</span>
          <div class="flex gap-2">
            <Badge id="hover" color="none">{ServerUrl || "Onbekend"}</Badge>
            {#if serverStatus}
              <Badge color="green" rounded class="px-2.5 py-0.5">
                <Indicator color="green" size="xs" class="me-1" />Verbonden
              </Badge>
            {:else}
              <Badge color="red" rounded class="px-2.5 py-0.5">
                <Indicator color="red" size="xs" class="me-1" />Niet verbonden
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
      </div>
    </div>

    <div class="flex justify-between items-center">
      <!-- Reset Application Button -->
      <Button color="red" on:click={() => (showResetModal = true)}>
        Reset Applicatie
      </Button>

      <div class="flex items-center gap-4">
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
    </div>
  </form>
{/if}

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
