<script lang="ts">
    import { route } from '../stores/RouterStore';
    import Home from '../routes/Home.svelte';
    import Error from '../routes/Error.svelte';
    import Schedule from '../routes/Schedule.svelte';
    import Setup from '../routes/Setup.svelte';

    let currentRoute;

    $: $route, currentRoute = $route.path; // Automatically re-render when route changes.

    const routes = {
        '/': Home,
        '/error': Error,
        '/schedule': Schedule,
        '/setup': Setup,
    };

    let CurrentComponent;

    // Listen for changes in route and update CurrentComponent
    $: CurrentComponent = routes[currentRoute] || Home;
    $: console.log('Current route:', currentRoute, '| Params:', $route.params);
</script>

<svelte:component this={CurrentComponent} />

