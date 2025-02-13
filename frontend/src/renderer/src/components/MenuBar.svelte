<script lang="ts">
  export let timeVisible = true;
  import { internetStatus, serverStatus } from "../stores/connection.store";
  import { time } from "../stores/time.store";

</script>

<div class="grid grid-cols-3 grid-flow justify-between items-center">
  <!-- App Title -->
  <div class="justify-self-start px-3 py-2 bg-primary-700 rounded-lg text-white">
    <p>Mijn Rooster</p>
  </div>

  <!-- Time -->
  <div class="justify-self-center">
    {#if timeVisible}
      <h1 class="text-2xl font-extrabold text-center w-full">
        {$time}
      </h1>
    {/if}
  </div>

  <!-- Status -->
  <div class="justify-self-end px-3 flex items-center gap-2">
    {#if $internetStatus}
      {#if $serverStatus}
        <span class="w-2 h-2 rounded-full bg-green-500"></span>
        <p>Online</p>
      {:else}
        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
        <p>No Server</p>
      {/if}
    {:else}
      <span class="w-2 h-2 rounded-full bg-red-500"></span>
      <p>Offline</p>
    {/if}
  </div>
</div>
