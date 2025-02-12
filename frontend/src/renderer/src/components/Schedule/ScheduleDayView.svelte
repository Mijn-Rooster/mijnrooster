<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { Button } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from "flowbite-svelte-icons";
  import { onMount } from "svelte";
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import { Spinner } from "flowbite-svelte";
  import { retrieveSchedule } from "../../services/api.service";
  import ErrorBanner from "../ErrorBanner.svelte";
  import type { ErrorModel } from "../../models/error.model";
  import { user } from "../../stores/user.store"; // Import the user store

  let schedule: ScheduleItemModel[] = [];

  const userId: number = 2022036; 
  let isLoading: boolean = false;
  let error: ErrorModel | null = null;

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

  /**
   * Loads the schedule for a specific user within a time range.
   * Sets loading state during the operation and handles errors.
   *
   * @async
   * @function loadSchedule
   * @returns {Promise<void>}
   *
   * Uses:
   * - retrieveSchedule() to fetch schedule data
   * - isLoading state to track loading status
   * - schedule store to save retrieved data
   * - error store to save potential errors
   * - userId, todayStartUnix, todayEndUnix for request parameters
   */
  function loadSchedule() {
    isLoading = true;
    retrieveSchedule(userId, todayStartUnix, todayEndUnix)
      .then((data) => {
        schedule = data;
      })
      .catch((err) => {
        error = err;
      })
      .finally(() => {
        isLoading = false;
      });
  }

  /**
   * Lifecycle function that runs when component is mounted.
   * Initializes the schedule by calling loadSchedule() asynchronously.
   * @see loadSchedule
   */
  onMount(async () => {
    loadSchedule();
  });

  /**
   * Navigates to the previous day in the schedule view.
   * Updates the date range by subtracting 24 hours (86400 seconds) from both start and end timestamps.
   * Updates the displayed date string using Dutch locale formatting.
   * Triggers a schedule reload for the new date range.
   */
  async function previousDay() {
    todayStartUnix -= 86400;
    todayEndUnix -= 86400;
    currentDate = new Date(todayStartUnix * 1000).toLocaleDateString("nl-NL", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
    loadSchedule();
  }

  /**
   * Navigates to the next day in the schedule view
   * Updates the unix timestamps for start and end of day by adding 24 hours (86400 seconds)
   * Updates the displayed date string to the new date in Dutch format
   * Reloads the schedule data for the new date
   */
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

<div class="mx-auto w-full max-w-[1000px] flex flex-col gap-4">
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
  {#if error}
    <ErrorBanner {error} />
  {/if}
</div>
