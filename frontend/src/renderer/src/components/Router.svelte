<script lang="ts">
  import { route } from "../stores/router.store";
  import Home from "../routes/Home.svelte";
  import Error from "../routes/Error.svelte";
  import Schedule from "../routes/Schedule.svelte";
  import Setup from "../routes/Setup.svelte";
  import Inloggen from "../routes/Inloggen.svelte";
  import type { SvelteComponent } from "svelte";

  let currentRoute: string;

  $: $route, (currentRoute = $route.path); // Automatically re-render when route changes.

  /**
   * Routes configuration object mapping URL paths to Svelte components.
   * Declare all routes here!
   */
  const routes: Record<string, typeof SvelteComponent<any, any, any>> = {
    "/": Home,
    "/error": Error,
    "/schedule": Schedule,
    "/setup": Setup,
    "/inloggen": Inloggen,	
  };

  let CurrentComponent;

  // Listen for changes in route and update CurrentComponent
  $: CurrentComponent = routes[currentRoute] || Home;
  $: console.log("Current route:", currentRoute, "| Params:", $route.params);
</script>

<svelte:component this={CurrentComponent} />
