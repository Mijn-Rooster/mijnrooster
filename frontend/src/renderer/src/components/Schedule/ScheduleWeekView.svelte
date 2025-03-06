<script lang="ts">
  import { Button, Spinner } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from "flowbite-svelte-icons";
  import { onMount } from "svelte";
  import type { ErrorModel } from "../../models/error.model";
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import type { UserModel } from "../../models/user.model";
  import { retrieveSchedule } from "../../services/api.service";
  import ErrorToast from "../ErrorToast.svelte";
  import ScheduleItemSmall from "./ScheduleItemSmall.svelte";

  export let user: UserModel;

  let schedule: ScheduleItemModel[] = [];

  const userId: string = user?.code || "";
  let isLoading: boolean = false;
  let error: ErrorModel | null = null;

  let fetchController: AbortController | null = null;

  let weekStartUnix = getStartOfWeekUnix(new Date());
  let weekEndUnix = getEndOfWeekUnix(new Date());
  $: weekNumber = getWeek(weekStartUnix);
  $: currentMonth = getCurrentMonth(weekEndUnix);
  const weekdayOrder = ["ma", "di", "wo", "do", "vr"];

  /**
   * Computes a reactive value that groups schedule items by day of the week.
   * @param {ScheduleItemModel[]} schedule - Array of schedule items to be grouped
   * @returns {Record<string, ScheduleItemModel[]>} An object where:
   * - Keys are lowercase Dutch abbreviated weekday names (e.g., 'ma', 'di', 'wo')
   * - Values are arrays of schedule items occurring on that day
   * The items are grouped by converting their Unix timestamp (start) to a Date object
   * and extracting the weekday.
   */
  $: groupedSchedule = schedule.reduce(
    (groups, item) => {
      const date = new Date(item.start * 1000);
      const day = date
        .toLocaleDateString("nl-NL", { weekday: "short" })
        .toLowerCase();

      if (!groups[day]) {
        groups[day] = [];
      }
      groups[day].push(item);
      return groups;
    },
    {} as Record<string, ScheduleItemModel[]>,
  );

  /**
   * Sorts events in groupedSchedule by their start time for each day.
   * This is a reactive statement that triggers whenever groupedSchedule changes.
   * @param {Object} groupedSchedule - Object containing schedule events grouped by day
   * @param {Date} groupedSchedule[].start - Start time of each event
   */
  $: Object.keys(groupedSchedule).forEach((day) => {
    groupedSchedule[day].sort((a, b) => a.start - b.start);
  });

  /**
   * Formats a day label by combining the day name with its corresponding date
   * based on the week's start date.
   *
   * @param {string} day - The name of the day to format
   * @returns {string} The formatted day label (e.g., "Monday 15")
   *
   * Uses a global weekStartUnix timestamp and weekdayOrder array to calculate
   * the correct date for the given day within the week.
   */
  function formatDayLabel(day: string): string {
    const date = new Date(weekStartUnix * 1000);
    const dayIndex = weekdayOrder.indexOf(day.toLowerCase());
    date.setDate(date.getDate() + dayIndex);
    return `${day} ${date.getDate()}`;
  }

  /**
   * Determines if the given day matches the current day.
   * @param {string} day - The day of the week to check (case-insensitive)
   * @returns {boolean} True if the given day is today, false otherwise
   *
   * Uses the weekStartUnix timestamp and weekdayOrder array (expected to be in scope)
   * to calculate the date for the given day and compare it with today's date.
   */
  function isCurrentDay(day: string): boolean {
    const today = new Date();
    const date = new Date(weekStartUnix * 1000);
    const dayIndex = weekdayOrder.indexOf(day.toLowerCase());
    date.setDate(date.getDate() + dayIndex);
    return date.toDateString() === today.toDateString();
  }

  /**
   * Calculates the Unix timestamp (in seconds) for the start of the week (Monday) containing the given date.
   *
   * @param {Date} date - The date to calculate the start of week from
   * @returns {number} Unix timestamp in seconds representing Monday 00:00:00 of the week containing the input date
   *
   * @example
   * // Returns Unix timestamp for Monday 00:00:00 of the current week
   * const timestamp = getStartOfWeekUnix(new Date());
   */
  function getStartOfWeekUnix(date: Date): number {
    const dayOfWeek = date.getDay();
    const diffToMonday = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
    const monday = new Date(date);
    monday.setDate(date.getDate() - diffToMonday);
    return monday.setHours(0, 0, 0, 0) / 1000;
  }

  /**
   * Calculates the Unix timestamp (in seconds) for the end of the week (Friday 23:59:59)
   * for a given date. The week is considered to end on Friday.
   *
   * @param {Date} date - The input date to calculate the end of week from
   * @returns {number} Unix timestamp in seconds representing Friday 23:59:59 of the same week
   *
   * If the input date is:
   * - Sunday: Calculates next Friday
   * - Monday through Friday: Calculates coming Friday
   * - Saturday: Calculates next Friday
   */
  function getEndOfWeekUnix(date: Date): number {
    const dayOfWeek = date.getDay();
    const diffToFriday = dayOfWeek === 0 ? 6 : 5 - dayOfWeek;
    const friday = new Date(date);
    friday.setDate(date.getDate() + diffToFriday);
    return friday.setHours(23, 59, 59, 0) / 1000;
  }

  /**
   * Calculates the week number for a given Unix timestamp
   * @param {number} unix - Unix timestamp in seconds
   * @returns {number} Week number (1-53)
   *
   * The calculation works by:
   * 1. Converting Unix timestamp to milliseconds and creating Date object
   * 2. Getting first day of the year for the given date
   * 3. Calculating days elapsed since start of year
   * 4. Adjusting for day of week offset and calculating week number
   */
  function getWeek(unix: number): number {
    const date = new Date(unix * 1000);
    const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
    const days = Math.floor(
      (date.getTime() - firstDayOfYear.getTime()) / (24 * 60 * 60 * 1000),
    );
    return Math.ceil((days + firstDayOfYear.getDay() + 1) / 7);
  }

  /**
   * Converts a Unix timestamp to a formatted date string in Dutch locale
   * @param {number} unix - The Unix timestamp in seconds
   * @returns {string} Formatted date string (e.g., "ma 1 jan")
   */
  function getCurrentMonth(unix: number): string {
    return new Date(unix * 1000).toLocaleDateString("nl-NL", {
      month: "long",
      year: "numeric",
    });
  }

  /**
   * Loads the schedule for a specific week.
   *
   * This function handles the asynchronous fetching of schedule data:
   * - Aborts any existing fetch request
   * - Shows loading state while fetching
   * - Updates schedule data on success
   * - Handles errors (ignoring abort errors)
   *
   * @async
   * @function loadSchedule
   * @uses {AbortController} fetchController - Controls fetch request cancellation
   * @uses {boolean} isLoading - Loading state indicator
   * @uses {function} retrieveSchedule - External function to fetch schedule data
   * @uses {string} userId - User identifier
   * @uses {number} weekStartUnix - Week start timestamp
   * @uses {number} weekEndUnix - Week end timestamp
   * @uses {Object} schedule - Store for schedule data
   * @uses {Error|null} error - Store for error state
   */
  async function loadSchedule() {
    if (fetchController) {
      fetchController.abort();
    }
    fetchController = new AbortController();

    isLoading = true;
    retrieveSchedule(userId, weekStartUnix, weekEndUnix, fetchController.signal)
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
   * Lifecycle function that runs when the component is mounted.
   * Initializes the schedule by calling loadSchedule() function.
   * This ensures the schedule data is loaded when the component is first rendered.
   */
  onMount(() => {
    loadSchedule();
  });

  /**
   * Navigates to the previous week in the schedule by:
   * 1. Subtracting 7 days (in seconds) from the week start timestamp
   * 2. Subtracting 7 days (in seconds) from the week end timestamp
   * 3. Reloading the schedule with the new date range
   */

  function previousWeek() {
    weekStartUnix -= 7 * 24 * 60 * 60;
    weekEndUnix -= 7 * 24 * 60 * 60;
    loadSchedule();
  }

  /**
   * Advances the schedule view to the next week by:
   * 1. Incrementing weekStartUnix by 7 days (in seconds)
   * 2. Incrementing weekEndUnix by 7 days (in seconds)
   * 3. Triggering a schedule reload
   */
  function nextWeek() {
    weekStartUnix += 7 * 24 * 60 * 60;
    weekEndUnix += 7 * 24 * 60 * 60;
    loadSchedule();
  }
</script>

<div class="mx-auto w-full max-w-[1000px] flex flex-col gap-4">
  <!-- Date navigation -->
  <div class="flex justify-between my-5 align-content-center">
    <!-- Previous -->
    <Button class="p-2 w-10" on:click={previousWeek} data-numpad="previous">
      <ArrowLeftOutline />
    </Button>

    <!-- Current date -->
    <h2 class="text-xl font-bold text-center">
      week {weekNumber} - {currentMonth}
    </h2>

    <!-- Next -->
    <Button class="p-2 w-10" on:click={nextWeek} data-numpad="next">
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
      <p class="text-lg text-gray-500">Geen rooster gevonden voor deze week</p>
    </div>
  {:else}
    <div class="grid grid-cols-5 gap-4 min-w-[800px]">
      {#each weekdayOrder as day}
        <div class="flex flex-col gap-2">
            <h3 class="font-bold text-lg text-center border-b py-2 sticky top-0 bg-white">
            <span class={isCurrentDay(day) ? 'bg-primary-50 rounded-lg py-1 px-2' : ''}>
              {formatDayLabel(day)}
            </span>
            </h3>
          <div class="flex flex-col gap-2 overflow-y-auto">
            {#if groupedSchedule[day]}
              {#each groupedSchedule[day] as item}
                <ScheduleItemSmall {item} />
              {/each}
            {:else}
              <p class="text-sm text-gray-500 text-center">Geen lessen</p>
            {/if}
          </div>
        </div>
      {/each}
    </div>
  {/if}

  <!-- Error message -->
  {#if error}
    <ErrorToast {error} />
  {/if}
</div>
