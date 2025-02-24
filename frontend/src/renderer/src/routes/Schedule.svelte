<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/router.store";
  import { Button, Footer, Alert, Toast } from "flowbite-svelte";
  import { ArrowLeftToBracketOutline } from "flowbite-svelte-icons";
  import Schedule from "../components/Schedule/Schedule.svelte";
  import type { UserModel } from "../models/user.model";
  import { route } from "../stores/router.store";
  import { onMount, onDestroy } from "svelte";
  import { core } from "../stores/core.store";
  import { get } from "svelte/store";
  import { clearHandlers, registerScheduleHandlers } from "../services/numpad.service";

  let user: UserModel = $route.params.user;
  let logoutTimer: NodeJS.Timeout;
  let warningTimer: NodeJS.Timeout;
  let showWarning = false;
  const AUTO_LOGOUT_ENABLED = get(core).autoLogout;
  const LOGOUT_DELAY = get(core).logoutTimeOut * 1000;
  const WARNING_DELAY = LOGOUT_DELAY - 5000; // Show warning 5 seconds before logout

  /**
   * Resets the auto-logout timers and warning state.
   *
   * This function manages two timers:
   * - Warning timer: Shows a warning message after WARNING_DELAY milliseconds of inactivity
   * - Logout timer: Redirects user to home page after LOGOUT_DELAY milliseconds of inactivity
   *
   * Both timers are cleared before being reset to prevent multiple concurrent timers.
   * The warning state is also reset to false when this function is called.
   */
  function resetLogoutTimer() {
    if (logoutTimer) clearTimeout(logoutTimer);
    if (warningTimer) clearTimeout(warningTimer);
    showWarning = false;

    warningTimer = setTimeout(() => {
      showWarning = true;
    }, WARNING_DELAY);

    logoutTimer = setTimeout(() => {
      navigate("/");
    }, LOGOUT_DELAY);
  }

  /**
   * Handles user activity by resetting the auto-logout timer.
   * This function should be called whenever the user performs any action
   * to prevent automatic logout while the user is actively using the application.
   */
  function handleUserActivity() {
    resetLogoutTimer();
  }

  /**
   * Lifecycle function that sets up event listeners when the component is mounted:
   * 
   * 1. If auto logout is enabled:
   *    - Resets the logout timer
   *    - Adds event listeners for user activity tracking (mouse, keyboard, touch, scroll)
   *    to prevent automatic logout
   * 
   * 2. If numpad control is enabled in core settings:
   *    - Registers schedule-specific keyboard handlers
   */
  onMount(() => {
    if (AUTO_LOGOUT_ENABLED) {
      resetLogoutTimer();
      window.addEventListener("mousemove", handleUserActivity);
      window.addEventListener("mousedown", handleUserActivity);
      window.addEventListener("keydown", handleUserActivity);
      window.addEventListener("touchstart", handleUserActivity);
      window.addEventListener("scroll", handleUserActivity);
    }
    if ($core.numPadControl) {
      registerScheduleHandlers();
    }
  });

  /**
   * Cleanup function executed when component is destroyed.
   * If auto logout is enabled:
   * - Clears logout and warning timers
   * - Removes all user activity event listeners (mousemove, mousedown, keypress, touchstart, scroll)
   * Finally calls clearHandlers() to perform additional cleanup
   */
  onDestroy(() => {
    if (AUTO_LOGOUT_ENABLED) {
      if (logoutTimer) clearTimeout(logoutTimer);
      if (warningTimer) clearTimeout(warningTimer);
      window.removeEventListener("mousemove", handleUserActivity);
      window.removeEventListener("mousedown", handleUserActivity);
      window.removeEventListener("keydown", handleUserActivity);
      window.removeEventListener("touchstart", handleUserActivity);
      window.removeEventListener("scroll", handleUserActivity);
    }
    clearHandlers();
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

<!-- svelte-ignore a11y-no-static-element-interactions -->
<div
  on:mousemove={handleUserActivity}
  on:keydown={handleUserActivity}
  class="h-full w-full"
>
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
