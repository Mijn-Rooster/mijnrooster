<script lang="ts">
  import { Toast } from "flowbite-svelte";
  import { ChevronDownOutline, CloseCircleSolid } from "flowbite-svelte-icons";
  import { fade } from "svelte/transition";
  import type { ErrorModel } from "../models/error.model";

  export let error: ErrorModel | null = null;
  let showDetails = false;

  let timeout: NodeJS.Timeout;

  $: {
    if (error) {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        error = null;
        showDetails = false;
      }, 10000);
    }
  }
</script>

{#if error}
  <Toast
    color="red"
    position="top-right"
    class="bg-red-50 border border-red-200 rounded-lg shadow-sm"
    transition={fade}
  >
    <svelte:fragment slot="icon">
      <CloseCircleSolid class="w-5 h-5" />
      <span class="sr-only">Error icon</span>
    </svelte:fragment>
    <div class="ml-3 text-sm font-normal">
      <div class="flex items-center">
        {error.message}
        {#if error.details}
          <button
            class="ml-2"
            on:click={() => (showDetails = !showDetails)}
            type="button"
          >
            <ChevronDownOutline
              class="w-3 h-3 transition-transform {showDetails
                ? 'rotate-180'
                : ''}"
            />
          </button>
        {/if}
      </div>
      {#if error.details && showDetails}
        <p class="mt-1 text-xs text-red-600">
          {error.details}
        </p>
      {/if}
    </div>
  </Toast>
{/if}
