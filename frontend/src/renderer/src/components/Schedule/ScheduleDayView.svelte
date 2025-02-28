<script lang="ts">
  import { Button, Spinner } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from "flowbite-svelte-icons";
  import { onMount } from "svelte";
  import type { ErrorModel } from "../../models/error.model";
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import type { UserModel } from "../../models/user.model";
  import { retrieveSchedule } from "../../services/api.service";
  import ErrorToast from "../ErrorToast.svelte";
  import ScheduleItem from "./ScheduleItem.svelte";

  export let user: UserModel;

  let schedule: ScheduleItemModel[] = [];

  const userId: string = user?.code || "";
  let isLoading: boolean = false;
  let error: ErrorModel | null = null;

  let fetchController: AbortController | null = null;

  let todayStartUnix = new Date().setHours(0, 0, 0, 0) / 1000;
  let todayEndUnix = new Date().setHours(23, 59, 59, 0) / 1000;
  $: currentDate = getCurrentDate(todayStartUnix);

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
  async function loadSchedule() {
    // Abort previous fetch if it exists
    if (fetchController) {
      fetchController.abort();
    }
    fetchController = new AbortController();

    isLoading = true;
    retrieveSchedule(
      userId,
      todayStartUnix,
      todayEndUnix,
      fetchController.signal,
    )
      .then((data) => {
        schedule = data;
        error = null;
        isLoading = false;
      })
      .catch((err) => {
        // Ignore abort errors
        if (err.name !== "AbortError") {
          error = err;
        }
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
    loadSchedule();
  }

  function getCurrentDate(unix: number) {
    return new Date(unix * 1000).toLocaleDateString("nl-NL", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
  }
</script>

<div class="mx-auto w-full max-w-[1000px] flex flex-col gap-4">
  <!-- Date navigation -->
  <div class="flex justify-between my-5 align-content-center">
    <!-- Previous -->
    <Button class="p-2 w-10" on:click={previousDay} data-numpad="previous">
      <ArrowLeftOutline />
    </Button>

    <!-- Current date -->
    <h2 class="text-xl font-bold text-center">{currentDate}</h2>

    <!-- Next -->
    <Button class="p-2 w-10" on:click={nextDay} data-numpad="next">
      <ArrowRightOutline />
    </Button>
  </div>
</div>

<div
  class="mx-auto w-full max-w-[1000px] flex flex-col gap-4 overflow-y-auto h-[calc(100%-50px)]"
  data-numpad="scroll-window"
>
  <!-- Schedule -->
  {#if isLoading}
    <div class="flex justify-center items-center w-full h-96">
      <Spinner />
    </div>
  {:else if schedule.length === 0}
    <div class="flex justify-center items-center w-full h-96">
      <p class="text-lg text-gray-500">Geen lessen gevonden voor vandaag</p>
    </div>
  {:else}
    {#each schedule as item}
      <ScheduleItem {item} />
    {/each}
  {/if}

  <!-- Error message -->
  {#if error}
    <ErrorToast {error} />
  {/if}
</div>
