<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { Button } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from "flowbite-svelte-icons";
  import { getCurrentDate } from "../../services/TimeService";
  import { onMount } from "svelte";
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import { Spinner } from "flowbite-svelte";

  let schedule: ScheduleItemModel[] = [];

  const userId = 138563;
  let isLoading = false;
  let todayStartUnix = new Date(getCurrentDate()).setHours(0, 0, 0, 0) / 1000;
  let todayEndUnix = new Date(getCurrentDate()).setHours(23, 59, 59, 0) / 1000;
  let currentDate = new Date(todayStartUnix * 1000).toLocaleDateString(
    "nl-NL",
    {
      day: "numeric",
      month: "long",
      year: "numeric",
    },
  );

  onMount(async () => {
    isLoading = true;
    try {
      schedule = await retrieveSchedule(userId);
    } finally {
      isLoading = false;
    }
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
    isLoading = true;
    schedule = [];
    try {
      schedule = await retrieveSchedule(userId);
    } finally {
      isLoading = false;
    }
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
    isLoading = true;
    schedule = [];
    try {
      schedule = await retrieveSchedule(userId);
    } finally {
      isLoading = false;
    }
  }

  async function retrieveSchedule(user: string | number) {
    const url = `http://localhost:8000/v1/schedule/${user}?start=${todayStartUnix}&end=${todayEndUnix}`;

    const response = await fetch(url, {
      method: "GET",
      headers: {
      accept: "application/json",
      Authorization: "Bearer oqkd1ogtDkOUcsa33HOdXvt76uXiTdfwxYGMqWem",
      },
    });

    if (!response.ok) {
      console.error("Schedule fetch failed:", response.status);
      return;
    }

    const responsedata = await response.json();

    // Sort on lesson start time
    responsedata.data.sort((a, b) => a.start - b.start);

    return responsedata.data;
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
  style="height: calc(100% - 280px);"
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
</div>
