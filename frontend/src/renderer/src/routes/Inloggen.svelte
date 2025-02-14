<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/router.store";
  import ErrorCard from "../components/ErrorCard.svelte";
  import { Button, Footer, Label, Input } from "flowbite-svelte";
  import { ArrowLeftOutline, UserSolid } from "flowbite-svelte-icons";
  import { retrieveUserInfo } from "../services/api.service";
  import type { ErrorModel } from "../models/error.model";
  import { Spinner } from "flowbite-svelte";

  let error: ErrorModel | null = null;
  let isLoading: boolean = false;
  let leerlingnummer: string = "";

  async function handleSubmit(event: SubmitEvent) {
    event.preventDefault();
    isLoading = true;
    error = null;

    retrieveUserInfo(leerlingnummer)
      .then((userInfo) => {
        navigate("/schedule", { user: userInfo });
      })
      .catch((err) => {
        error = {
          message: "Onjuist leerlingnummer",
          details: "",
        };
      })
      .finally(() => {
        isLoading = false;
      });
  }
</script>

<MenuBar />

<main>
  <div class="my-5">
    <div
      class="w-full max-w-md p-4 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800 my-1"
    >
      <h2
        class="text-3xl font-semibold text-center text-gray-800 dark:text-white"
      >
        Inloggen
      </h2>
      <form class="mt-6" on:submit={handleSubmit}>
        <div>
          <Label
            for="leerlingnummer"
            class="block text-sm text-gray-800 dark:text-gray-200"
            >leerlingnummer</Label
          >
          <Input
            type="text"
            id="leerlingnummer"
            bind:value={leerlingnummer}
            placeholder="leerlingnummer"
            class="w-full px-4 py-2 mt-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:border-primary-400 focus:outline-none focus:ring dark:text-gray-300"
          >
            <UserSolid slot="left" class="w-4 h-4" />
          </Input>
        </div>

        <div class="mt-6">
            <Button
            type="submit"
            class="w-full py-2 text-white bg-primary-500 rounded-md hover:bg-primary-600 focus:outline-none focus:ring"
            disabled={isLoading}
            >
            {#if isLoading}
              <Spinner class="w-5 h-5" />
              &nbsp; Laden...
            {:else}
              Bekijk je rooster!
            {/if}
            </Button>
        </div>
      </form>
    </div>
  </div>

  <!-- If there's an error, show ErrorCard -->
  {#if error}
    <div class="flex justify-center">
      <div class="w-full max-w-md">
        <ErrorCard {error} />
      </div>
    </div>
  {/if}
</main>

<Footer
  class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600"
>
  <Button class="gap-2 px-2" on:click={() => navigate("/")}>
    <ArrowLeftOutline />Terug
  </Button>
</Footer>
