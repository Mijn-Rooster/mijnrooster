<script lang="ts">
    import { Button, Label, Input, Select } from "flowbite-svelte";
    import { retrieveSchoolList } from "../services/api.service";
    import { core } from "../stores/core.store";
    import type { SchoolModel } from "../models/school.model";
    import type { ErrorModel } from "../models/error.model";
    import ErrorCard from "./ErrorCard.svelte";
    import { onMount } from "svelte";
  
    export let close: () => void;  // Add this line to receive the close function

    let schools: SchoolModel[] = [];
    let selectedSchool: number | null = $core.schoolId;
    let serverUrl: string = $core.serverUrl ?? '';
    let isLoading = false;
    let error: ErrorModel | null = null;
  
    onMount(async () => {
      try {
        schools = await retrieveSchoolList();
      } catch (err) {
        error = err as ErrorModel;
      }
    });
  
    async function handleSubmit(event: SubmitEvent) {
      event.preventDefault();
      isLoading = true;
      error = null;

      try {
        const selectedSchoolData = schools.find(s => s.schoolId === selectedSchool);
        
        if (!selectedSchoolData) {
          throw { 
            message: "Selecteer een school",
            details: "Er moet een school geselecteerd worden om verder te gaan"
          };
        }

        if (!serverUrl) {
          throw {
            message: "Vul een server URL in",
            details: "Er moet een server URL ingevuld worden om verder te gaan"
          };
        }

        // Update the core store
        core.set({
          ...$core,
          schoolId: selectedSchoolData.schoolId,
          schoolInYearId: selectedSchoolData.schoolInSchoolYearId,
          serverUrl: serverUrl
        });

        // Verify the update
        console.log('Updated core store:', $core);
        
        if (close) close();
      } catch (err) {
        error = err as ErrorModel;
        console.error('Settings update failed:', err);
      } finally {
        isLoading = false;
      }
    }
</script>
  
  <form class="space-y-6" on:submit={handleSubmit}>
    <div class="space-y-6">
      <div>
        <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-4">
          Server instellingen
        </h3>
        <Label class="space-y-2">
          <span>Server URL</span>
          <Input
            type="url"
            bind:value={serverUrl}
            placeholder="https://localhost:8000"
            required
          />
        </Label>
      </div>
  
      <div>
        <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-4">
          School instellingen
        </h3>
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
      </div>
  
      {#if error}
        <ErrorCard {error} />
      {/if}
    </div>
  
    <div class="flex justify-end gap-4">
      <Button type="submit" disabled={isLoading}>
        {#if isLoading}
          Opslaan...
        {:else}
          Opslaan
        {/if}
      </Button>
    </div>
  </form>