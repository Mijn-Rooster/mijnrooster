<script lang="ts">
  import { Button, Label, Input, Footer } from "flowbite-svelte";
  import { navigate } from "../../stores/router.store";
  import { ArrowRightOutline } from "flowbite-svelte-icons";
  import { check } from "../../services/api.service";
  import { core } from "../../stores/core.store";

  let serverUrl = "";
  let serverPassword = "";
  let isLoading = false;
  let errorMessage = "";

  async function handleSubmit(event: SubmitEvent) {
    event.preventDefault();
    isLoading = true;
    errorMessage = "";

    try {
      const result = await check(serverUrl, serverPassword);

      switch (result.status) {
        case "ok":
          // Proceed to next step
          navigate("/setup/second");
          break;
        case "auth_error":
          errorMessage = "Het koppelwachtwoord is onjuist";
          break;
        default:
          errorMessage = result.message;
          break;
      }
    } catch (error) {
      errorMessage = "An unexpected error occurred";
    } finally {
      isLoading = false;

      // Update the core store with new values
      core.update((state) => ({
        ...state,
        serverUrl,
        serverPassword,
      }));
    }
  }
</script>

<div
  class="mx-auto pt-10 w-full max-w-[800px] max-h-200px flex flex-col gap-10 overflow-y-auto"
>
  <div>
    <h1 class="text-5xl font-bold mb-2">Welkom bij Mijn Rooster!</h1>
    <p class="text-xl w-full">
      Om te beginnen hebben we wat informatie nodig. <br /> We gaan eerst verbinding
      maken met de Mijn Rooster server.
    </p>
  </div>

  <form class="flex flex-col space-y-6" on:submit={handleSubmit}>
    <Label class="space-y-2">
      <span>Server URL</span>
      <Input
        type="url"
        bind:value={serverUrl}
        placeholder="https://localhost:8000"
        required
      />
    </Label>
    <Label class="space-y-2">
      <span>Koppelwachtwoord</span>
      <Input
        type="password"
        bind:value={serverPassword}
        placeholder="Welkom01!"
        required
      />
    </Label>
    {#if errorMessage}
      <p class="text-red-500">{errorMessage}</p>
    {/if}
  </form>
</div>

<Footer
  class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow flex items-center justify-end"
>
  <Button
    type="submit"
    class="gap-2 px-2"
    disabled={isLoading}
    on:click={() => document.querySelector("form")?.requestSubmit()}
  >
    {#if isLoading}
      Controleren...
    {:else}
      Verder<ArrowRightOutline />
    {/if}
  </Button>
</Footer>
