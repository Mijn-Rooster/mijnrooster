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
   * Sets up event listeners for user activity tracking when component is mounted.
   * If auto-logout is enabled, this initializes the logout timer and attaches
   * multiple event listeners to reset the timer on user interaction.
   *
   * Event listeners are added for:
   * - Mouse movement
   * - Mouse clicks
   * - Keyboard input
   * - Touch events
   * - Scroll events
   *
   * @requires AUTO_LOGOUT_ENABLED - Boolean flag to enable/disable auto logout
   * @requires resetLogoutTimer - Function to reset the auto logout timer
   * @requires handleUserActivity - Event handler function for user interactions
   */
  onMount(() => {
    if (AUTO_LOGOUT_ENABLED) {
      resetLogoutTimer();
      window.addEventListener("mousemove", handleUserActivity);
      window.addEventListener("mousedown", handleUserActivity);
      window.addEventListener("keypress", handleUserActivity);
      window.addEventListener("touchstart", handleUserActivity);
      window.addEventListener("scroll", handleUserActivity);
    }
  });

  /**
   * Cleanup function that runs when component is destroyed.
   * If auto logout is enabled:
   * - Clears any existing logout and warning timers
   * - Removes all event listeners that were tracking user activity:
   *   - Mouse movement
   *   - Mouse clicks
   *   - Keyboard presses
   *   - Touch events
   *   - Scroll events
   * This prevents memory leaks and ensures proper cleanup of auto-logout functionality.
   */
  onDestroy(() => {
    if (AUTO_LOGOUT_ENABLED) {
      if (logoutTimer) clearTimeout(logoutTimer);
      if (warningTimer) clearTimeout(warningTimer);
      window.removeEventListener("mousemove", handleUserActivity);
      window.removeEventListener("mousedown", handleUserActivity);
      window.removeEventListener("keypress", handleUserActivity);
      window.removeEventListener("touchstart", handleUserActivity);
      window.removeEventListener("scroll", handleUserActivity);
    }
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
    <Button class="gap-2 px-2" on:click={() => navigate("/")}
      ><ArrowLeftToBracketOutline />Uitloggen</Button
    >
  </Footer>
</div>
