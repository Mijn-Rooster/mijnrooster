<script lang="ts">
  import { Button, Input, Label, Modal } from "flowbite-svelte";
  import { onMount } from "svelte";
  import ErrorCard from "./components/ErrorCard.svelte";
  import ErrorToast from "./components/ErrorToast.svelte";
  import Router from "./components/Router.svelte";
  import SettingsModal from "./components/SettingsModal.svelte";
  import type { ErrorModel } from "./models/error.model";
  import { checkAdminPassword } from "./services/core.service";
  import {
    destroyNumpadControls,
    initNumpadControls,
  } from "./services/numpad.service";
  import { connectSavedScanner } from "./services/scanner.service";
  import { core, isSetupComplete } from "./stores/core.store";

  let showPasswordModal = false;
  let showSettingsModal = false;
  let password = "";
  let error: ErrorModel | null = null;
  let scannerConnectError: ErrorModel | null = null;

  /**
   * Reactive statement that handles numpad controls initialization and cleanup
   * When $core.numPadControl is true, initializes numpad controls
   * When $core.numPadControl is false, destroys numpad controls
   * @reactive $core.numPadControl
   */

  $: {
    if ($core.numPadControl) {
      initNumpadControls();
    } else {
      destroyNumpadControls();
    }
  }

  $: {
    if ($core.barcodeScanner) {
      connectScanner();
    }
  }

  async function connectScanner() {
    try {
      const connected = await connectSavedScanner();
      if (!connected && $core.barcodeScanner) {
        scannerConnectError = {
          message: `Kon niet verbinden met scanner: ${$core.barcodeScanner.name}`
        };
      }
    } catch (err) {
      console.error("Error connecting saved scanner:", err);
    }
  }

  /**
   * Initializes the application when component is mounted.
   * Sets up:
   * - Event listener for settings dialog
   *   - Shows password modal if setup is complete
   *   - Resets error state and password field
   */
  onMount(() => {
    window.api.onOpenSettings(() => {
      if (isSetupComplete()) {
        showPasswordModal = true;
        error = null;
        password = "";
      }
    });
  });

  /**
   * Handles the submission of the admin password.
   * Validates the password using checkAdminPassword function.
   * If password is correct:
   * - Closes the password modal
   * - Opens the settings modal
   * - Clears any previous errors
   * If password is incorrect:
   * - Sets error message "Onjuist wachtwoord"
   * In both cases, clears the password field afterward.
   */
  async function handlePasswordSubmit() {
    if (await checkAdminPassword(password)) {
      showPasswordModal = false;
      showSettingsModal = true;
      error = null;
    } else {
      error = {
        message: "Onjuist wachtwoord",
      };
    }
    password = "";
  }
</script>

<Router />

<ErrorToast error={scannerConnectError} />

<!-- Password Check Modal -->
<Modal
  bind:open={showPasswordModal}
  size="md"
  title="Instellingen"
  outsideclose
>
  <form
    class="flex flex-col space-y-6"
    on:submit|preventDefault={handlePasswordSubmit}
  >
    <div>
      <Label for="password" class="mb-2">Voer het beheerderswachtwoord in</Label
      >
      <Input
        type="password"
        id="password"
        bind:value={password}
        placeholder="Wachtwoord"
        required
      />
    </div>

    {#if error}
      <ErrorCard {error} size="sm" />
    {/if}

    <Button type="submit" class="w-full">Bevestigen</Button>
  </form>
</Modal>

<!-- Settings Modal -->
<SettingsModal bind:open={showSettingsModal}/>

<style>
  /* Remove focus outline */
  :global(svg:focus) {
    outline: none !important;
  }

  /* Remove focus outline on buttons */
  :global(button:focus) {
    outline: none !important;
  }
</style>
