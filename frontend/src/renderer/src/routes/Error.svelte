<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
    import { navigate } from "../stores/RouterStore";
    import { Button } from 'flowbite-svelte';
</script>

<MenuBar />

<h1>Error</h1>

<Button on:click={() => navigate("/")}
    >Go to Home</Button>