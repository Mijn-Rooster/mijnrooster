<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { ButtonGroup, Button } from 'flowbite-svelte';
  import { ArrowLeftOutline, ArrowRightOutline } from 'flowbite-svelte-icons';
  import { getCurrentDate } from "../../services/TimeService";
  import { navigate } from "../../stores/RouterStore";
  import { onMount } from "svelte";
  import { user } from "../../stores/UserStore";

  let currentDate = getCurrentDate();
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
    // Fetch schedule data from the API
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

<!-- Schedule items -->
{#each schedule as item}
  <ScheduleItem {item} />
{/each}