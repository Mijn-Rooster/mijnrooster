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
  import type { UserModel } from "../../models/user.model";
  
  export let user: UserModel;

  let schedule: { [key: string]: ScheduleItemModel[] } = {};

  const userId: string = user?.code || "";
  let isLoading: boolean = false;
  let error: ErrorModel | null = null;

  let fetchController: AbortController | null = null;

  async function loadSchedule() {
    // Abort previous fetch if it exists
    if (fetchController) {
      fetchController.abort();
    }
    fetchController = new AbortController();

    isLoading = true;
    schedule = {};

    const startOfWeek = new Date(todayStartUnix * 1000);
    const dayOfWeek = startOfWeek.getDay();
    const diffToMonday = (dayOfWeek === 0 ? 6 : dayOfWeek - 1);
    const monday = new Date(startOfWeek);
    monday.setDate(startOfWeek.getDate() - diffToMonday);
    const friday = new Date(monday);
    friday.setDate(monday.getDate() + 4);

    const startOfWeekUnix = monday.setHours(0, 0, 0, 0) / 1000;
    const endOfWeekUnix = friday.setHours(23, 59, 59, 0) / 1000;

    try {
      for (let day = startOfWeekUnix; day <= endOfWeekUnix; day += 86400) {
        const data = await retrieveSchedule(userId, day, day + 86399, fetchController.signal);
        const dateKey = new Date(day * 1000).toLocaleDateString("nl-NL", {
          day: "numeric",
          month: "long",
          year: "numeric",
        });
        schedule[dateKey] = data;
      }
      isLoading = false;
    } catch (err) {
      // Ignore abort errors
      if (err instanceof Error && err.name !== "AbortError") {
        error = { message: err.message, details: err.stack || "" };
      }
      isLoading = false;
    }
  }

  /**
   * Lifecycle function that runs when component is mounted.
   * Initializes the schedule by calling loadSchedule() asynchronously.
   * @see loadSchedule
   */
  onMount(async () => {
    loadSchedule();
  });

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

  let weekStartUnix = new Date().setHours(0, 0, 0, 0) / 1000;
  let weekEndUnix = weekStartUnix + 6 * 86400;
  let currentWeek = getWeekLabel(weekStartUnix);
  let weekDates = getWeekDates(weekStartUnix);

  function getWeekLabel(startUnix: number): string {
    const startDate = new Date(startUnix * 1000);
    const month = startDate.toLocaleDateString("nl-NL", { month: "long" });
    const weekNumber = getWeekNumber(startDate);
    return `${month} | week ${weekNumber}`;
  }

  function getWeekNumber(date: Date): number {
    const startOfYear = new Date(date.getFullYear(), 0, 1);
    const pastDaysOfYear = (date.getTime() - startOfYear.getTime()) / 86400000;
    return Math.ceil((pastDaysOfYear + startOfYear.getDay() + 1) / 7);
  }

  function getWeekDates(startUnix: number): string[] {
    const dates = [];
    const days = ["zo", "ma", "di", "wo", "do", "vr", "za"];
    for (let i = 0; i <= 4; i++) { // Loop from 1 (Monday) to 5 (Friday)
      const date = new Date((startUnix + i * 86400) * 1000);
      const day = days[date.getDay()];
      const dayOfMonth = date.getDate();
      dates.push(`${day} ${dayOfMonth}`);
    }
    return dates;
  }

  async function previousWeek() {
    weekStartUnix -= 7 * 86400;
    weekEndUnix -= 7 * 86400;
    currentWeek = getWeekLabel(weekStartUnix);
    weekDates = getWeekDates(weekStartUnix);
    loadSchedule();
  }

  async function nextWeek() {
    weekStartUnix += 7 * 86400; 
    weekEndUnix += 7 * 86400;
    currentWeek = getWeekLabel(weekStartUnix);
    weekDates = getWeekDates(weekStartUnix);
    loadSchedule();
  }

</script>

<div class="mx-auto w-full max-w-[1000px] flex flex-col gap-4">
  <!-- Date navigation -->
  <div class="flex justify-between my-5 align-content-center">
    <!-- Previous -->
    <Button class="p-2 w-10" on:click={previousWeek}>
      <ArrowLeftOutline />
    </Button>

    <!-- Current week -->
    <h2 class="text-xl font-bold text-center">{currentWeek}</h2>

    <!-- Next -->
    <Button class="p-2 w-10" on:click={nextWeek}>
      <ArrowRightOutline />
    </Button>
  </div>

  <!-- Week schedule -->
  <div class="grid grid-cols-5 gap-4">
    {#each weekDates as date}
      <div>
        <h3 class="text-lg font-bold text-center">{date}</h3>
        {#if isLoading}
          <div class="text-center"><Spinner /></div>
        {:else if schedule[date] && schedule[date].length === 0}
          <p class="text-center">Geen lessen gevonden</p>
        {:else if schedule[date]}
          {#each schedule[date] as item}
            <ScheduleItem {item} />
          {/each}
        {/if}
      </div>
    {/each}
  </div>

  <!-- Error message -->
  {#if error}
    <ErrorBanner {error} />
  {/if}
</div>