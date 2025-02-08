<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/router.store";
  import { Button, Footer } from "flowbite-svelte";
  import { ArrowLeftToBracketOutline } from "flowbite-svelte-icons";
  import Schedule from "../components/Schedule/Schedule.svelte";
</script>

<MenuBar />

<h1 class="text-4xl font-bold text-center my-8">Welkom!</h1>

<Schedule/>

<Footer
  class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600"
>
  <Button class="gap-2 px-2" on:click={() => navigate("/")}
    ><ArrowLeftToBracketOutline />Uitloggen</Button
  >
</Footer>
