<script lang="ts">
  import { Alert } from "flowbite-svelte";
  import {
    CloseCircleSolid,
    ChevronDownOutline,
  } from "flowbite-svelte-icons";
  import { fade } from "svelte/transition";
  import type { ErrorModel } from "../models/error.model";

  export let error: ErrorModel | null = null;
  let showDetails = false;
</script>

{#if error}
  <div transition:fade>
    <Alert border color="red">
      <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-2 align-center">
          <CloseCircleSolid slot="icon" class="w-5 h-5" />
          <span class="font-medium">{error.message}</span>
        </div>
        {#if error.details}
          <button
        type="button"
        on:click={() => (showDetails = !showDetails)}
          >
        <ChevronDownOutline
          class="w-3 h-3 transition-transform {showDetails ? 'rotate-180' : ''}"
        />
          </button>
        {/if}
      </div>
      {#if showDetails && error.details} 
      <div class="mt-1 ml-7" transition:fade>
        <p class="text-xs">
          {error.details}
        </p>
      </div>
      {/if}
    </Alert>
  </div>
{/if}
