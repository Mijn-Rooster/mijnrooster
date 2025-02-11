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
  import { timeStore } from "../stores/time.store";

  // Check if Mijn Rooster setup is completed
  if (isSetupComplete() < 2) {
    navigate("/setup", { setupStep: isSetupComplete() });
  }
</script>

<MenuBar timeVisible={false} />

<h1 class="text-4xl font-extrabold text-center w-full">{$timeStore.time}</h1>
<h2 class="text-xl font-bold text-center w-full">{$timeStore.date}</h2>

<div class="flex-grow flex flex-col items-center justify-center">
  <ProfileCardSolid class="size-40" style="color: #291c5b;" />
  <p class="text-2xl text-center">
    Scan je schoolpas om je rooster te bekijken
  </p>
</div>

<Footer
  class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600"
>
  <Button
    on:click={() => navigate("/schedule")}
    class="bg-primary-700 text-white gap-2 px-2"
  >
    <ArrowLeftToBracketOutline />
    Inloggen met leerlingnummer
  </Button>
</Footer>
