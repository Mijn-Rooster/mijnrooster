<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/RouterStore";
  import Schedule from "../components/Schedule/Schedule.svelte";
  import { Button } from "flowbite-svelte";
</script>

<MenuBar />
<Schedule />
<Button on:click={() => navigate("/")}>Go to Home</Button>
