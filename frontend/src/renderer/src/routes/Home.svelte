<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/RouterStore";
</script>

<h1>Home</h1>

<MenuBar />

<!-- svelte-ignore a11y-click-events-have-key-events a11y-no-static-element-interactions a11y-missing-attribute-->
<button on:click={() => navigate("/schedule")}
  >[] Go to schedule page</button>
<button on:click={() => navigate("/error")}
  >[] Go to error page</button>
<button on:click={() => navigate("/setup")}
    >[] Go to setup page</button>
