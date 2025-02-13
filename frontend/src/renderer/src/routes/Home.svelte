<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/router.store";
  import { Button } from "flowbite-svelte";
  import {
    ProfileCardSolid,
    ArrowLeftToBracketOutline,
  } from "flowbite-svelte-icons";
  import { Footer } from "flowbite-svelte";
  import { isSetupComplete } from "../stores/core.store";
  import { time, date } from "../stores/time.store";
  import { internetStatus, serverStatus } from "../stores/connection.store";
  import { Modal } from "flowbite-svelte";
  import { Signal_wifi_4_bar, Signal_wifi_statusbar_connected_no_internet_4, Signal_wifi_0_bar } from 'svelte-google-materialdesign-icons';

  // Check if Mijn Rooster setup is completed
  if (isSetupComplete() < 2) {
    navigate("/setup", { setupStep: isSetupComplete() });
  }

  // Disable functionality when offline
  let offline = false;
  $: {
    if (!$internetStatus || !$serverStatus) {
      offline = true;
    } else {
      offline = false;
    }
  }
</script>

<MenuBar timeVisible={false} />

<h1 class="text-4xl font-extrabold text-center w-full">{$time}</h1>
<h2 class="text-xl font-bold text-center w-full">{$date}</h2>

<div class="flex-grow flex flex-col items-center justify-center">
  <ProfileCardSolid class="size-40" style="color: #291c5b;" />
  <p class="text-2xl text-center">
    Scan je schoolpas om je rooster te bekijken
  </p>
</div>

<Modal title="Buiten gebruik" bind:open={offline} dismissable={false}>
  <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
    We konden geen contact opnemen met de server. Controleer uw netwerkverbinding.
  </p>
  <div class="border-t border-gray-200 dark:border-gray-600 my-4"></div>
  <div class="mt-2 text-xs leading-relaxed text-gray-500 dark:text-gray-400 flex space-between justify-around gap-4">
    <div class="grid grid-cols-1 gap-2">
      <p class="flex items-center justify-center gap-2">
        {#if $internetStatus}
          <Signal_wifi_4_bar class="inline w-6 h-6" />
        {:else}
          <Signal_wifi_0_bar class="inline w-6 h-6" />
        {/if}
      </p>
      <p class="text-center">
        <b>Internet status:</b> {$internetStatus ? "Verbonden" : "Niet verbonden"}
      </p>
    </div>
    <div class="grid grid-cols-1 gap-2">
      <p class="flex items-center justify-center gap-2">
        {#if $serverStatus}
          <Signal_wifi_4_bar class="inline w-6 h-6" />
        {:else}
          <Signal_wifi_statusbar_connected_no_internet_4 class="inline w-6 h-6" />
        {/if}
      </p>
      <p class="text-center">
        <b>Server status:</b> {$serverStatus ? "Online" : "Offline"}
      </p>
    </div>
  </div>
</Modal>

<Footer
  class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600"
>
  <Button
    on:click={() => navigate("/schedule")}
    class="bg-primary-700 text-white gap-2 px-2"
    disabled={offline}
  >
    <ArrowLeftToBracketOutline />
    Inloggen met leerlingnummer
  </Button>
</Footer>
