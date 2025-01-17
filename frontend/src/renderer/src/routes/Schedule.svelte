<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/RouterStore";
  import ScheduleItem from "../components/Schedule/ScheduleItem.svelte";
  import { Button, Footer } from "flowbite-svelte";
  import { ArrowLeftToBracketOutline } from 'flowbite-svelte-icons';
  import { user } from "../stores/UserStore";
  import { onMount } from "svelte";

  let username = '';
  let schedule = [];

  $: $user, username = $user.username;

  onMount(async () => {
    console.log('Logged in user:', username);
    // Get the current date in UNIX timestamp
    const currentDateUnix = Math.floor(Date.now() / 1000);
    // Construct the URL
    const url = `http://545959.leerlingsites.nl/pws/api/v1/schedule/${username}?start=${currentDateUnix}&end=${currentDateUnix + 86400}`; // 86400 seconds = 1 day
    console.log('Fetching schedule from URL:', url);
    // Fetch schedule data from the API with authorization header
    const response = await fetch(url, {
      headers: {
        'Authorization': 'Bearer YOUR_ACCESS_TOKEN' // Replace with your actual token
      }
    });
    schedule = await response.json();
  });

  function previousDay() {
    // Logic to navigate to the previous day
  }

  function nextDay() {
    // Logic to navigate to the next day
  }
</script>

<MenuBar />

<h1 class="text-4xl font-bold text-center my-8">Welcome, {username}!</h1>

<div class="mx-auto w-[100%] max-w-[1000px]">
  {#each schedule as item}
    <ScheduleItem {item} />
  {/each}
</div>

<Footer class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
  <Button class="gap-2 px-2" on:click={() => navigate("/")}><ArrowLeftToBracketOutline/>Uitloggen</Button>
</Footer>
