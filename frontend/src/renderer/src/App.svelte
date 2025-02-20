<script lang="ts">
  import Router from "./components/Router.svelte";
  import { onMount } from "svelte";
  import { core } from "./stores/core.store";
  import { Modal, Button, Input, Label } from "flowbite-svelte";
  import type { ErrorModel } from "./models/error.model";
  import ErrorCard from "./components/ErrorCard.svelte";
  import SettingsMenu from "./components/SettingsMenu.svelte";
  import { checkAdminPassword } from "./services/core.service";

  let showPasswordModal = false;
  let showSettingsModal = false;
  let password = "";
  let error: ErrorModel | null = null;

  onMount(() => {
    window.api.onOpenSettings(() => {
      showPasswordModal = true;
      error = null;
      password = "";
    });
  });

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

<!-- Password Check Modal -->
<Modal
  bind:open={showPasswordModal}
  size="md"
  title="Beheerderswachtwoord"
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
      <ErrorCard {error} />
    {/if}

    <Button type="submit" class="w-full">Bevestigen</Button>
  </form>
</Modal>

<!-- Settings Modal -->
<Modal bind:open={showSettingsModal} size="lg" title="Instellingen">
  <div class="p-4">
    <SettingsMenu />
  </div>
</Modal>
