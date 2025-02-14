<script lang="ts">
  import {
    ProfileCardSolid,
    ArrowLeftToBracketOutline,
    UserSolid,
  } from "flowbite-svelte-icons";
  import { isSetupComplete } from "../stores/core.store";
  import { time, date } from "../stores/time.store";
  import { internetStatus, serverStatus } from "../stores/connection.store";
  import { Modal } from "flowbite-svelte";
  import {
    Signal_wifi_4_bar,
    Signal_wifi_statusbar_connected_no_internet_4,
    Signal_wifi_0_bar,
  } from "svelte-google-materialdesign-icons";
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/router.store";
  import ErrorCard from "../components/ErrorCard.svelte";
  import { Button, Footer, Label, Input } from "flowbite-svelte";
  import { retrieveUserInfo } from "../services/api.service";
  import type { ErrorModel } from "../models/error.model";
  import { Spinner } from "flowbite-svelte";

  let error: ErrorModel | null = null;
  let isLoading: boolean = false;
  let leerlingnummer: string = "";

  async function handleSubmit(event: SubmitEvent) {
    event.preventDefault();
    if (leerlingnummer.length < 1) return;

    isLoading = true;
    error = null;

    retrieveUserInfo(leerlingnummer)
      .then((userInfo) => {
        navigate("/schedule", { user: userInfo });
      })
      .catch((err) => {
        error = {
          message: "Onjuist leerlingnummer",
          details: "",
        };
      })
      .finally(() => {
        isLoading = false;
      });
  }

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

<!-- Time and Date -->
<div class="text-center mt-4">
  <h1 class="text-4xl font-extrabold w-full text-gray-800 dark:text-gray-200">
    {$time}
  </h1>
  <h2 class="text-xl font-bold w-full text-gray-600 dark:text-gray-300">
    {$date}
  </h2>
</div>

<main class="flex-grow flex flex-col justify-center items-center overflow-y-auto">
  <div
    class="my-5 w-full max-w-md p-10 mx-auto bg-white rounded-lg border border-gray-200"
  >
    <div class="flex justify-center mb-4">
      <ProfileCardSolid size="lg" class="w-16 h-16 text-primary-600" />
    </div>
    <h2
      class="text-3xl font-semibold text-center text-gray-800 dark:text-white"
    >
      Scan je schoolpas
    </h2>
    <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-1">
      Houd je schoolpas tegen de kaartlezer
    </p>
  </div>
  <div
    class="my-5 w-full max-w-md p-10 mx-auto bg-white rounded-lg border border-gray-200"
  >
    <h2
      class="text-3xl font-semibold text-center text-gray-800 dark:text-white"
    >
      Inloggen
    </h2>
    <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-1">
      Voer je leerlingnummer in om verder te gaan
    </p>

    <form class="mt-6" on:submit={handleSubmit}>
      <div>
        <Label
          for="leerlingnummer"
          class="block text-sm text-gray-800 dark:text-gray-200"
          >Leerlingnummer</Label
        >
        <Input
          type="text"
          id="leerlingnummer"
          bind:value={leerlingnummer}
          placeholder="bijv. 2022036"
          class="w-full px-4 py-2 mt-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:border-primary-400 focus:outline-none focus:ring dark:text-gray-300"
        >
          <UserSolid slot="left" class="w-4 h-4" />
        </Input>
      </div>

      <div class="mt-6">
        <Button
          type="submit"
          class="w-full py-2 text-white bg-primary-500 rounded-md hover:bg-primary-600 focus:outline-none focus:ring"
          disabled={isLoading}
        >
          {#if isLoading}
            <Spinner class="w-5 h-5" />
            &nbsp; Laden...
          {:else}
            Bekijk je rooster!
          {/if}
        </Button>
      </div>
    </form>
  </div>
  <div class="w-full max-w-md mt-2">
    <!-- If there's an error, show ErrorCard -->
    {#if error}
      <ErrorCard {error} />
    {/if}
  </div>
</main>

<Modal title="Buiten gebruik" bind:open={offline} dismissable={false}>
  <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
    We konden geen contact opnemen met de server. Controleer uw
    netwerkverbinding.
  </p>
  <div class="border-t border-gray-200 dark:border-gray-600 my-4"></div>
  <div
    class="mt-2 text-xs leading-relaxed text-gray-500 dark:text-gray-400 flex space-between justify-around gap-4"
  >
    <div class="grid grid-cols-1 gap-2">
      <p class="flex items-center justify-center gap-2">
        {#if $internetStatus}
          <Signal_wifi_4_bar class="inline w-6 h-6" />
        {:else}
          <Signal_wifi_0_bar class="inline w-6 h-6" />
        {/if}
      </p>
      <p class="text-center">
        <b>Internet status:</b>
        {$internetStatus ? "Verbonden" : "Niet verbonden"}
      </p>
    </div>
    <div class="grid grid-cols-1 gap-2">
      <p class="flex items-center justify-center gap-2">
        {#if $serverStatus}
          <Signal_wifi_4_bar class="inline w-6 h-6" />
        {:else}
          <Signal_wifi_statusbar_connected_no_internet_4
            class="inline w-6 h-6"
          />
        {/if}
      </p>
      <p class="text-center">
        <b>Server status:</b>
        {$serverStatus ? "Online" : "Offline"}
      </p>
    </div>
  </div>
</Modal>
