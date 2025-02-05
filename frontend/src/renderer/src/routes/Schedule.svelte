<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/RouterStore";
  import ScheduleItem from "../components/Schedule/ScheduleItem.svelte";
  import { Button, Footer } from "flowbite-svelte";
  import { ArrowLeftToBracketOutline } from "flowbite-svelte-icons";
  import type { ScheduleItemModel } from "../models/scheduleItem.model";
  import { onMount } from "svelte";

  let username = "";
  let schedule: ScheduleItemModel[] = [];

  onMount(async () => {
    const currentDateUnix = Math.floor(Date.now() / 1000);
    const url = `http://localhost:8000/v1/schedule/138563?start=${currentDateUnix}&end=${currentDateUnix + 86400}`;
    console.log("Fetching schedule from URL:", url);
    
    const response = await fetch(url, {
      method: 'GET',
      headers: {
        'accept': 'application/json',
        'Authorization': 'Bearer oqkd1ogtDkOUcsa33HOdXvt76uXiTdfwxYGMqWem'
      },
      mode: 'cors',
      credentials: 'include'
    });
    
    if (!response.ok) {
      console.error('Schedule fetch failed:', response.status);
      return;
    }

    const data = await response.json();
    schedule = data.data;

    // Log the fetched data and the schedule
    console.log("Fetched data:", data, "Schedule:", schedule);

    // Sort on lesson start time
    schedule.sort((a, b) => a.start - b.start);
  });

  function previousDay() {
    // Logic to navigate to the previous day
  }

  function nextDay() {
    // Logic to navigate to the next day
  }
</script>

<MenuBar />

<h1 class="text-4xl font-bold text-center my-8">Welkom, {username}!</h1>
<div
  class="mx-auto w-full max-w-[1000px] flex flex-col gap-4 overflow-y-auto"
  style="height: calc(100% - 250px);"
>
  {#each schedule as item}
    <ScheduleItem {item} />
  {/each}
</div>

<Footer
  class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600"
>
  <Button class="gap-2 px-2" on:click={() => navigate("/")}
    ><ArrowLeftToBracketOutline />Uitloggen</Button
  >
</Footer>
