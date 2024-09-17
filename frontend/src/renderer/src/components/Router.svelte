<script lang="ts">
    import { route } from '../stores/RouterStore';
    import Home from '../routes/Home.svelte';
    import Test from '../routes/Test.svelte';
    import Login from '../routes/Login.svelte';

    let currentRoute;

    $: $route, currentRoute = $route.path; // Automatically re-render when route changes.

    const routes = {
        '/': Home,
        '/login': Login,
        '/test': Test,
    };

    let CurrentComponent;

    // Listen for changes in route and update CurrentComponent
    $: CurrentComponent = routes[currentRoute] || Home;

    console.log('Current route:', currentRoute);
</script>

<svelte:component this={CurrentComponent} />

