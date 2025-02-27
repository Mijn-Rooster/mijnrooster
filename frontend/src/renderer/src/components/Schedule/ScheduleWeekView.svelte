<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { Button } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from "flowbite-svelte-icons";
  import { onMount } from "svelte";
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import { Spinner } from "flowbite-svelte";
  import { retrieveSchedule } from "../../services/api.service";
  import ErrorToast from "../ErrorToast.svelte";
  import type { ErrorModel } from "../../models/error.model";
  import type { UserModel } from "../../models/user.model";
  
  export let user: UserModel;

  let schedule: { [key: string]: ScheduleItemModel[] } = {};

  const userId: string = user?.code || "";
  let isLoading: boolean = false;
  let error: ErrorModel | null = null;

  let fetchController: AbortController | null = null;

  // Function to calculate the start of the week (Monday 00:00) UNIX timestamp
  function getStartOfWeekUnix(date: Date): number {
    const dayOfWeek = date.getDay();
    const diffToMonday = (dayOfWeek === 0 ? 6 : dayOfWeek - 1);
    const monday = new Date(date);
    monday.setDate(date.getDate() - diffToMonday);
    return monday.setHours(0, 0, 0, 0) / 1000;
  }

  // Initialize the start of the week UNIX timestamp
  let weekStartUnix = getStartOfWeekUnix(new Date());

  // Function to load the schedule for the week
  async function loadSchedule() {
    // Abort previous fetch if it exists
    if (fetchController) {
      fetchController.abort();
    }
    fetchController = new AbortController();

    isLoading = true;
    schedule = {};

    // Calculate the end of the week (Friday 23:59) UNIX timestamp
    const monday = new Date(weekStartUnix * 1000);
    const friday = new Date(monday);
    friday.setDate(monday.getDate() + 4);
    const endOfWeekUnix = friday.setHours(23, 59, 59, 0) / 1000;

    try {
      // Fetch the schedule for the entire week
      const data = await retrieveSchedule(userId, weekStartUnix, endOfWeekUnix, fetchController.signal);
      data.forEach((item: ScheduleItemModel) => {
        if (item.start === undefined) {
          console.error("Item startTime is undefined:", item); // Debugging statement
        } else {
          
          // Ensure item.startTime is a valid number
          if (!isNaN(item.start)) {
            // Create a key for each day in the format 'YYYYMMDD'
            const date = new Date(item.start * 1000);
            const dateKey = date.toLocaleDateString("nl-NL", {
              year: "numeric",
              month: "2-digit",
              day: "2-digit",
            }).replace(/-/g, '');
            
            // Initialize the array for the day if it doesn't exist
            if (!schedule[dateKey]) {
              schedule[dateKey] = [];
            }
            // Add the item to the corresponding day
            schedule[dateKey].push(item);
          } else {
            console.error("Invalid startTime:", item.start); // Debugging statement
          }
        }
      });
      
      isLoading = false;
    } catch (err) {
      // Handle errors, ignoring abort errors
      if (err instanceof Error && err.name !== "AbortError") {
        error = { message: err.message, details: err.stack || "" };
        console.error("Error loading schedule:", error); // Debugging statement
      }
      isLoading = false;
    }
  }

  // Lifecycle function that runs when the component is mounted
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

  let currentWeek = getWeekLabel(weekStartUnix);
  let weekDates = getWeekDates(weekStartUnix);

  // Function to get the label for the current week
  function getWeekLabel(startUnix: number): string {
    const startDate = new Date(startUnix * 1000);
    const month = startDate.toLocaleDateString("nl-NL", { month: "long" });
    const weekNumber = getWeekNumber(startDate);
    return `${month} | week ${weekNumber}`;
  }

  // Function to get the week number
  function getWeekNumber(date: Date): number {
    const startOfYear = new Date(date.getFullYear(), 0, 1);
    const pastDaysOfYear = (date.getTime() - startOfYear.getTime()) / 86400000;
    return Math.ceil((pastDaysOfYear + startOfYear.getDay() + 1) / 7);
  }

  // Function to get the dates for the week
  function getWeekDates(startUnix: number): { displayDate: string, dateKey: string }[] {
    const dates = [];
    const days = ["zo", "ma", "di", "wo", "do", "vr", "za"]; // Abbreviations for days of the week
    for (let i = 0; i <= 4; i++) { // Loop from Monday to Friday
      const date = new Date((startUnix + i * 86400) * 1000);
      const day = days[date.getDay()];
      const displayDate = `${day} ${date.getDate()}`;
      const dateKey = date.toLocaleDateString("nl-NL", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
      }).replace(/-/g, '');
      dates.push({ displayDate, dateKey });
    }
    return dates;
  }
  

  // Function to go to the previous week
  async function previousWeek() {
    weekStartUnix -= 7 * 86400;
    currentWeek = getWeekLabel(weekStartUnix);
    weekDates = getWeekDates(weekStartUnix);
    loadSchedule();
  }

  // Function to go to the next week
  async function nextWeek() {
    weekStartUnix += 7 * 86400; 
    currentWeek = getWeekLabel(weekStartUnix);
    weekDates = getWeekDates(weekStartUnix);
    loadSchedule();
  }

</script>

<style>
  .schedule-item {
    padding: 5px;
    margin-bottom: 3px;
    border-radius: 4px;
    background-color: #f9f9f9;
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  :global(.schedule-item span) {
    padding: 2px 4px;
    font-size: 14px;
    border-radius: 10px;
    background-color: #291c5b;
  }

  :global(.schedule-item div) {
    gap: 10px;
  }

</style>

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
    {#each weekDates as { displayDate, dateKey }}
      <div>
        <h3 class="text-lg font-bold text-center">{displayDate}</h3>
        {#if isLoading}
          <div class="text-center"><Spinner /></div>
        {:else if schedule[dateKey] && schedule[dateKey].length === 0}
          <p class="text-center">Geen lessen gevonden</p>
        {:else if schedule[dateKey]}
          {#each schedule[dateKey] as item}
            <div class="schedule-item">
              <ScheduleItem {item} />
            </div>
          {/each}
        {/if}
      </div>
    {/each}
  </div>

  <!-- Error message -->
  {#if error}
    <ErrorToast {error} />
  {/if}
</div>