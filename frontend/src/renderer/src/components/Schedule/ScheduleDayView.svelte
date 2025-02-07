<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { Button } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from "flowbite-svelte-icons";
  import { onMount } from "svelte";
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import { Spinner } from "flowbite-svelte";
  import { retrieveSchedule } from "../../services/api.service";
  import ErrorBanner from "../ErrorBanner.svelte";

  let schedule: ScheduleItemModel[] = [];

  const userId: number = 138563;
  let isLoading: boolean = false;
  let errorMessage: string = "";

  let todayStartUnix = new Date().setHours(0, 0, 0, 0) / 1000;
  let todayEndUnix = new Date().setHours(23, 59, 59, 0) / 1000;
  let currentDate = new Date(todayStartUnix * 1000).toLocaleDateString(
    "nl-NL",
    {
      day: "numeric",
      month: "long",
      year: "numeric",
    },
  );

  function loadSchedule() {
    isLoading = true;
    retrieveSchedule(userId, todayStartUnix, todayEndUnix)
      .then((data) => {
        schedule = data;
      })
      .catch((error) => {
        errorMessage = error;
      })
      .finally(() => {
        isLoading = false;
      });
  }

  onMount(async () => {
    loadSchedule();
  });

  async function previousDay() {
    // Logic to navigate to the previous day
    todayStartUnix -= 86400;
    todayEndUnix -= 86400;
    currentDate = new Date(todayStartUnix * 1000).toLocaleDateString("nl-NL", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
    loadSchedule();
  }

  async function nextDay() {
    // Logic to navigate to the next day
    todayStartUnix += 86400;
    todayEndUnix += 86400;
    currentDate = new Date(todayStartUnix * 1000).toLocaleDateString("nl-NL", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
    loadSchedule();
  }
</script>

<div
  class="mx-auto w-full max-w-[1000px] flex flex-col gap-4"
>
  <!-- Date navigation -->
  <div class="flex justify-between my-5 align-content-center">
    <!-- Previous -->
    <Button class="p-2 w-10" on:click={previousDay}>
      <ArrowLeftOutline />
    </Button>

    <!-- Current date -->
    <h2 class="text-xl font-bold text-center">{currentDate}</h2>

    <!-- Next -->
    <Button class="p-2 w-10" on:click={nextDay}>
      <ArrowRightOutline />
    </Button>
  </div>
</div>

<div
  class="mx-auto w-full max-w-[1000px] flex flex-col gap-4 overflow-y-auto"
  style="height: calc(100% - 300px);"
>
  <!-- Schedule -->
  {#if isLoading}
    <div class="text-center"><Spinner /></div>
  {:else if schedule.length === 0}
    <p class="text-center">Geen lessen gevonden</p>
  {:else}
    {#each schedule as item}
      <ScheduleItem {item} />
    {/each}
  {/if}

  <!-- Error message -->
  {#if errorMessage}
    <ErrorBanner message={errorMessage} />
  {/if}
</div>
