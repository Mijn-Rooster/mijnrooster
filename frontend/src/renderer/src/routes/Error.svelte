<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
    import { navigate } from "../stores/RouterStore";
    import { Button, Footer } from 'flowbite-svelte';
    import { ArrowLeftOutline } from 'flowbite-svelte-icons';
</script>

<MenuBar />

<h1>Error</h1>

<Footer class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
  <Button class="gap-2 px-2" on:click={() => navigate("/")}><ArrowLeftOutline/>Terug</Button>
</Footer>