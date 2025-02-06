<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
    import { navigate } from "../stores/RouterStore";
    import { Button, Footer } from 'flowbite-svelte';
    import { ArrowLeftOutline } from 'flowbite-svelte-icons';
    import { getCurrentTime, getCurrentDate } from "../services/time.service";
    import { onMount } from "svelte";

    $: currentDate = getCurrentDate()

    let currentTime: string;

    onMount(() => {
      currentTime = getCurrentTime();
      const interval = setInterval(() => {
        currentTime = getCurrentTime();
      }, 1000);
      return () => clearInterval(interval);
    });
</script>

<MenuBar timeVisible={true} />

<div class="mx-auto w-[100%] max-w-[1000px]">
  <h1 class="text-5xl font-bold mb-2">Oeps!</h1>
  <p class="text-xl w-full">Er is iets misgegaan.<br><br> Probeer het later opnieuw.</p>
</div>

<Footer class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
  <Button class="gap-2 px-2" on:click={() => navigate("/")}><ArrowLeftOutline/>Terug</Button>
</Footer>