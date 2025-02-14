<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/router.store";
  import { Button, Footer } from "flowbite-svelte";
  import { ArrowLeftToBracketOutline } from "flowbite-svelte-icons";
  import Schedule from "../components/Schedule/Schedule.svelte";
  import type { UserModel } from "../models/user.model";
  import { route } from "../stores/router.store";

  let user: UserModel = $route.params.user;
</script>

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
