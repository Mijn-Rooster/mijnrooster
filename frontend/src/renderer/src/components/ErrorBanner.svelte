<script lang="ts">
  import { Banner } from "flowbite-svelte";
  import {
    CloseCircleOutline,
    ChevronDownOutline,
  } from "flowbite-svelte-icons";
  import { fade } from "svelte/transition";
  import type { ErrorModel } from "../models/error.model";

  export let error: ErrorModel | null = null;
  let showDetails = false;

  $: {
    if (error) {
      setTimeout(() => {
        error = null;
        showDetails = false;
      }, 10000);
    }
  }
</script>

{#if error}
  <div transition:fade>
    <Banner position="absolute" color="gray">
      <p class="flex flex-col text-red-800">
        <span class="flex items-center text-sm font-medium">
          <span class="inline-flex p-1 me-3 bg-red-100 rounded-full">
            <CloseCircleOutline class="w-3 h-3 text-red-500" />
            <span class="sr-only">Error</span>
          </span>
          <span>{error.message}</span>
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
        </span>
        {#if error.details && showDetails}
          <span class="ml-8 text-xs mt-1 text-red-600" transition:fade>
            {error.details}
          </span>
        {/if}
      </p>
    </Banner>
  </div>
{/if}
