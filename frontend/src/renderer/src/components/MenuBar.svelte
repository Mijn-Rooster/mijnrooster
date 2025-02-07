<script lang="ts">
  export let timeVisible = true;
  import { getCurrentTime } from "../services/time.service";

  let currentTime: string;

  // Get current time
  $: currentTime = getCurrentTime();

</script>

<div class="grid grid-cols-3 grid-flow justify-between items-center">
  <!-- App Title -->
  <div class="justify-self-start px-3 py-2 bg-primary-700 rounded-lg text-white">
    <p>Mijn Rooster</p>
  </div>

  <!-- Time -->
  <div class="justify-self-center ">
    {#if timeVisible}
      <h1 class="text-2xl font-extrabold text-center w-full">{currentTime}</h1>
    {/if}
  </div>

  <!-- Status -->
  <div class="justify-self-end">
    <p>Online</p>
  </div>
</div>

