<script lang="ts">
  import { Button, Footer, Toast } from "flowbite-svelte";
  import { ArrowLeftToBracketOutline } from "flowbite-svelte-icons";
  import { onMount, onDestroy } from 'svelte';
  import MenuBar from "../components/MenuBar.svelte";
  import Schedule from "../components/Schedule/Schedule.svelte";
  import type { UserModel } from "../models/user.model";
  import { clearHandlers, registerScheduleHandlers } from "../services/numpad.service";
  import { initAutoLogout, destroyAutoLogout } from "../services/autologout.service";
  import { core } from "../stores/core.store";
  import { navigate, route } from "../stores/router.store";

  let user: UserModel = $route.params.user;
  let showWarning = false;

  /**
   * Reactive statement that manages keyboard event handlers for the schedule
   * When numPadControl store is enabled, registers schedule-related keyboard handlers
   * When disabled, clears all registered handlers
   * @reactive
   * @depends $core.numPadControl - Store value determining if numpad controls are enabled
   */
  $: {
    if ($core.numPadControl) {
      registerScheduleHandlers();
    } else {
      clearHandlers();
    }
  }

  /**
   * Reactive statement that watches the 'user' variable
   * Redirects to the home page ("/") if no user is authenticated
   * This acts as a route guard to protect the schedule page
   */
  $: {
    if (!user) {
      navigate("/");
    }
  }

  /**
   * Reactive statement that handles auto-logout functionality
   * When `$core.autoLogout` is true:
   * - Initializes auto-logout with callbacks for:
   *   - Warning display
   *   - Navigation to home page on logout
   *   - Reset warning state
   * When false:
   * - Destroys/cleans up auto-logout functionality
   * 
   * @reactive Depends on $core.autoLogout store value
   */
  $: {
    if ($core.autoLogout) {
      initAutoLogout(
        // Warning callback
        () => { showWarning = true; },
        // Logout callback
        () => { navigate("/"); },
        // Reset callback
        () => { showWarning = false; }
      );
    } else {
      destroyAutoLogout();
    }
  }

  /**
   * Cleanup function that runs when the component is destroyed.
   * Removes event handlers and cleans up auto logout functionality.
   * 
   * @function
   * Calls:
   * - clearHandlers(): Removes registered event handlers
   * - destroyAutoLogout(): Cleans up auto logout timer and related resources
   */
  onDestroy(() => {
    clearHandlers();
    destroyAutoLogout();
  });
</script>

{#if showWarning}
  <Toast
    color="dark"
    position="bottom-right"
    class="absolute bottom-right rounded-lg bg-secondary-100 z-30"
    dismissable={false}
    >Je wordt over 5 seconden automatisch uitgelogd wegens inactiviteit...</Toast
  >
{/if}

<div class="h-full w-full">
  <MenuBar />

  <h1 class="text-4xl font-bold text-center my-8">
    Welkom, {user.firstName.trim()}
    {user.prefix.trim()}
    {user.lastName.trim()}!
  </h1>

  <Schedule {user} />

  <Footer
    class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6"
  >
    <Button class="gap-2 px-2" on:click={() => navigate("/")} data-numpad="logout"
      ><ArrowLeftToBracketOutline />Uitloggen</Button
    >
  </Footer>
</div>