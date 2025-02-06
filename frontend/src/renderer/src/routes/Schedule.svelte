<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/RouterStore";
  import ScheduleItem from "../components/Schedule/ScheduleItem.svelte";
  import { Button, Footer } from "flowbite-svelte";
  import { ArrowLeftToBracketOutline } from "flowbite-svelte-icons";
  import type { ScheduleItemModel } from "../models/scheduleItem.model";
  import { onMount } from "svelte";
  import Schedule from "../components/Schedule/Schedule.svelte";

  let username = "";

  function previousDay() {
    // Logic to navigate to the previous day
  }

  function nextDay() {
    // Logic to navigate to the next day
  }
</script>

<MenuBar />

<h1 class="text-4xl font-bold text-center my-8">Welkom, {username}!</h1>

<Schedule/>

<Footer
  class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600"
>
  <Button class="gap-2 px-2" on:click={() => navigate("/")}
    ><ArrowLeftToBracketOutline />Uitloggen</Button
  >
</Footer>
