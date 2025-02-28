<script lang="ts">
  import { Button, Footer, Input, Label, Select } from "flowbite-svelte";
  import { ArrowRightOutline } from "flowbite-svelte-icons";
  import { onMount } from "svelte";
  import type { ErrorModel } from "../../models/error.model";
  import type { SchoolModel } from "../../models/school.model";
  import { retrieveSchoolList } from "../../services/api.service";
  import { getHash } from "../../services/core.service";
  import { core } from "../../stores/core.store";
  import { navigate } from "../../stores/router.store";
  import ErrorCard from "../ErrorCard.svelte";

  let schools: SchoolModel[] = [];
  let selectedSchool: number | null = null;
  let adminPassword: string = "";
  let isLoading = false;
  let error: ErrorModel | null = null;

  // Load schools when component mounts
  onMount(async () => {
    retrieveSchoolList()
      .then((data) => {
        schools = data;
      })
      .catch((err) => {
        error = err;
      });
  });

  async function handleSubmit(event: SubmitEvent) {
    event.preventDefault();
    isLoading = true;
    error = null;
    const selectedSchoolData = schools.find(
      (s) => s.schoolId === selectedSchool,
    );

    try {
      if (!selectedSchoolData) {
        throw { message: "Selecteer een school" };
      }

      if (adminPassword.length < 4) {
        throw {
          message: "Beheerderscode moet minimaal 4 tekens lang zijn",
        };
      }

      // Update the core store with the selected school and admin password
      const hashedPassword = await getHash(adminPassword);
      core.update((state) => ({
        ...state,
        schoolId: selectedSchoolData.schoolId,
        schoolInYearId: selectedSchoolData.schoolInSchoolYearId,
        adminPassword: hashedPassword,
      }));

      // Navigate to the main application if validation passes
      navigate("/");
    } catch (err) {
      error = err as ErrorModel;
    } finally {
      isLoading = false;
    }
  }
</script>

<div
  class="mx-auto pt-10 w-full max-w-[800px] max-h-200px flex flex-col gap-10 overflow-y-auto"
>
  <div>
    <h1 class="text-5xl font-bold mb-2">Welkom bij Mijn Rooster!</h1>
    <p class="text-xl w-full">
      Selecteer je school en stel een beheerderscode in. <br />
      Met deze code kun je later instellingen aanpassen.
    </p>
  </div>

  <form class="flex flex-col space-y-6" on:submit={handleSubmit}>
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

    <Label class="space-y-2">
      <span>Beheerderscode</span>
      <Input
        type="password"
        bind:value={adminPassword}
        placeholder="Minimaal 4 tekens"
        minlength={4}
        required
      />
    </Label>

    {#if error}
      <ErrorCard {error} />
    {/if}
  </form>
</div>

<Footer
  class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow flex items-center justify-end"
>
  <Button
    type="submit"
    class="gap-2 px-2"
    disabled={isLoading}
    on:click={() => document.querySelector("form")?.requestSubmit()}
  >
    {#if isLoading}
      Opslaan...
    {:else}
      Afronden<ArrowRightOutline />
    {/if}
  </Button>
</Footer>
