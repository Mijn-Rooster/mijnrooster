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

  function loadSchedule() {
    // Your existing loadSchedule logic here
  }

  onMount(async () => {
    loadSchedule();
  });
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
    {#each weekDates as date, index}
      <div>
        <h3 class="text-lg font-bold text-center">{date}</h3>
        <!-- Example schedule items for each day -->
        <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
          <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
            u1
          </span>
          <div class="flex flex-col items-center">
            <b>Bv</b>
            <p>Bv2 - pro</p>
          </div>
        </div>
        <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
          <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
            u2
          </span>
          <div class="flex flex-col items-center">
            <b>Bv</b>
            <p>Bv2 - pro</p>
          </div>
        </div>
        <!-- Add more schedule items as needed -->
      </div>
    {/each}
  </div>
</div>