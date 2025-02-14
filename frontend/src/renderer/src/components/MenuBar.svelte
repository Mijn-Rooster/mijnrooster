<script lang="ts">
  export let timeVisible = true;
  import { internetStatus, serverStatus } from "../stores/connection.store";
  import { time } from "../stores/time.store";
  import { Signal_wifi_4_bar, Signal_wifi_statusbar_connected_no_internet_4, Signal_wifi_0_bar } from "svelte-google-materialdesign-icons";
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
        <Signal_wifi_4_bar class="w-6 h-6 text-primary-700" />
      {:else}
        <Signal_wifi_statusbar_connected_no_internet_4 class="w-6 h-6 text-primary-700" />
      {/if}
    {:else}
      <Signal_wifi_0_bar class="w-6 h-6 text-primary-700" />
    {/if}
  </div>
</div>
