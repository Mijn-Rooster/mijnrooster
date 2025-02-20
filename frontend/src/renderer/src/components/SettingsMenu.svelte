<script lang="ts">
  import { Button, Label, Input, Select, Toggle, Modal } from "flowbite-svelte";
  import { retrieveSchoolList } from "../services/api.service";
  import { core, resetCoreStore } from "../stores/core.store";
  import type { SchoolModel } from "../models/school.model";
  import type { ErrorModel } from "../models/error.model";
  import ErrorCard from "./ErrorCard.svelte";
  import { onMount } from "svelte";
  import { getHash } from "../services/core.service";

  let schools: SchoolModel[] = [];
  let selectedSchool: number | null = null;
  let adminPassword: string = "";
  let weekView: boolean = false;
  let numPadControl: boolean = false;
  let isLoading = false;
  let isSaved = false;
  let error: ErrorModel | null = null;
  let showResetModal = false;

  // Load initial values from store
  onMount(async () => {
    try {
      schools = await retrieveSchoolList();
    } catch (err) {
      error = err as ErrorModel;
    }

    const coreValues = $core;
    selectedSchool = coreValues.schoolId;
    weekView = coreValues.weekView;
    numPadControl = coreValues.numPadControl;
  });

  async function saveSettings() {
    isLoading = true;
    try {
      const hashedPassword = adminPassword 
        ? await getHash(adminPassword)
        : null;
      
      core.update((state) => ({
        ...state,
        schoolId: selectedSchool,
        adminPassword: hashedPassword || state.adminPassword,
        weekView: weekView,
        numPadControl: numPadControl,
      }));

      isSaved = true;
      setTimeout(() => (isSaved = false), 3000);
    } catch (err) {
      error = err as ErrorModel;
    } finally {
      isLoading = false;
    }
  }

  function confirmReset() {
    resetCoreStore();
    showResetModal = false;
  }
</script>

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
      <span>Wijzig Admin Wachtwoord</span>
      <Input type="password" bind:value={adminPassword} placeholder="Nieuw wachtwoord (optioneel)" />
    </Label>

    <!-- Week View Toggle -->
    <Label class="flex items-center space-x-2">
      <Toggle bind:checked={weekView} />
      <span>Toon Weekoverzicht</span>
    </Label>

    <!-- NumPad Control Toggle -->
    <Label class="flex items-center space-x-2">
      <Toggle bind:checked={numPadControl} />
      <span>Gebruik NumPad Besturing</span>
    </Label>

    {#if error}
      <ErrorCard {error} />
    {/if}

    <div class="p-3 bg-yellow-100 text-yellow-900 rounded-lg text-sm">
      <strong>Opmerking:</strong> Om de server URL te wijzigen, moet je het apparaat resetten.
    </div>
  </div>

  <div class="flex justify-between items-center">
    <!-- Reset Application Button -->
    <Button color="red" on:click={() => (showResetModal = true)}>
      Reset Applicatie
    </Button>

    <div class="flex items-center gap-4">
      {#if isSaved}
        <span class="text-green-600 font-medium">Wijzigingen opgeslagen</span>
      {/if}

      <!-- Save Button -->
      <Button on:click={saveSettings} disabled={isLoading}>
        {#if isLoading}
          Opslaan...
        {:else}
          Opslaan
        {/if}
      </Button>
    </div>
  </div>
</form>

<!-- Reset Confirmation Modal -->
<Modal open={showResetModal} size="md" title="Reset Applicatie" dismissable={false}>
  <div class="p-6">
    <h3 class="text-lg font-semibold text-gray-900">Bevestig reset</h3>
    <p class="mt-2 text-sm text-gray-600">
      Weet je zeker dat je de applicatie wilt resetten? Dit verwijdert alle instellingen en je moet opnieuw verbinden.
    </p>
    <div class="mt-4 flex justify-end gap-2">
      <Button color="light" on:click={() => (showResetModal = false)}>Annuleren</Button>
      <Button color="red" on:click={confirmReset}>Resetten</Button>
    </div>
  </div>
</Modal>

