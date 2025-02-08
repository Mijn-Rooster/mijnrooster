<script lang="ts">
  import {
    CloseCircleOutline,
    ChevronDownOutline,
  } from "flowbite-svelte-icons";
  import { fade } from "svelte/transition";
  import type { ErrorModel } from "../models/error.model";

  export let error: ErrorModel | null = null;
  let showDetails = false;
</script>

{#if error}
  <div
    transition:fade
    class="p-4 bg-red-50 border border-red-200 rounded-lg shadow-sm dark:bg-red-900/10 dark:border-red-800"
  >
    <p class="flex flex-col text-red-800 dark:text-red-200">
      <span class="flex items-center text-sm font-medium">
        <span
          class="inline-flex p-1 me-3 bg-red-100 rounded-full dark:bg-red-900"
        >
          <CloseCircleOutline class="w-3 h-3 text-red-500 dark:text-red-300" />
          <span class="sr-only">Error</span>
        </span>
        <span>{error.message}</span>
        {#if error.details}
          <button
            type="button"
            class="ml-2"
            on:click={() => (showDetails = !showDetails)}
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
        <span
          class="ml-8 text-xs mt-1 text-red-600 dark:text-red-300"
          transition:fade
        >
          {error.details}
        </span>
      {/if}
    </p>
  </div>
{/if}
