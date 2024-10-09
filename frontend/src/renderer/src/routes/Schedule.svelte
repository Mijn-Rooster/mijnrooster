<script lang="ts">
    import MenuBar from "../components/MenuBar.svelte";
    import { navigate } from "../stores/RouterStore";
</script>

<h1>Schedule</h1>

<MenuBar />

<!-- svelte-ignore a11y-click-events-have-key-events a11y-no-static-element-interactions a11y-missing-attribute-->
<button on:click={() => navigate("/")}
    >[] Go to Home</button>