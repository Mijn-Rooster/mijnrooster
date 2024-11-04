<script lang="ts">
  export let timeVisible = true;
  import { onMount } from "svelte";
  import { getCurrentTime } from "../services/TimeService";
  import { navigate } from "../stores/RouterStore";

  let currentTime: string;

  onMount(() => {
    currentTime = getCurrentTime();
    const interval = setInterval(() => {
      currentTime = getCurrentTime();
    }, 1000);
    return () => clearInterval(interval);
  });
</script>

<div class="grid grid-cols-3 grid-flow justify-between items-center">
  <!-- App Title -->
  <button on:click={() => navigate("/home")} class="justify-self-start px-3 py-2 bg-primary-700 rounded-lg text-white">
    <p>Mijn Rooster</p>
  </button>

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

