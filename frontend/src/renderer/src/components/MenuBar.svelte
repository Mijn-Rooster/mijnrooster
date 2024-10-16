<script lang="ts">
  export let timeVisible = true;
  import { onMount } from "svelte";
  import { getCurrentTime } from "../services/TimeService";

  let currentTime: string;

  onMount(() => {
    currentTime = getCurrentTime();
    const interval = setInterval(() => {
      currentTime = getCurrentTime();
    }, 1000);
    return () => clearInterval(interval);
  });
</script>

<div class="flex flex-row justify-between items-center">
  <!-- App Title -->
  <div class="px-3 py-2 bg-primary-700 rounded-lg text-white">
    <p>Mijn Rooster</p>
  </div>
  <!-- Time -->
  <div>
    {#if timeVisible}
      <p>{currentTime}</p>
    {/if}
  </div>
  <!-- Status -->
  <div>
    <p>Online</p>
  </div>
</div>
