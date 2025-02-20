<script lang="ts">
  import { Button, Label, Input, Footer } from "flowbite-svelte";
  import { navigate } from "../../stores/router.store";
  import { ArrowRightOutline } from "flowbite-svelte-icons";
  import { connectionCheck } from "../../services/api.service";
  import { core } from "../../stores/core.store";
  import ErrorCard from "../ErrorCard.svelte";
  import type { ErrorModel } from "../../models/error.model";

  let serverUrl = "";
  let serverPassword = "";
  let isLoading = false;
  let error: ErrorModel | null = null;

  async function handleSubmit(event: SubmitEvent) {
    event.preventDefault();
    isLoading = true;
    error = null;

    connectionCheck(serverUrl, serverPassword)
      .then(() => {
        core.update((state) => ({
          ...state,
          serverUrl,
          serverPassword,
        }));
        
        navigate("/");
      })
      .catch((err) => {
        error = err;
      })
      .finally(() => {
        isLoading = false;
      });
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
        placeholder="Bijv. http://localhost:8000"
        required
      />
    </Label>
    <Label class="space-y-2">
      <span>Koppelwachtwoord</span>
      <Input
        type="password"
        bind:value={serverPassword}
        placeholder="Bijv. Welkom123"
        required
      />
    </Label>
    {#if error}
      <ErrorCard {error} />
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
