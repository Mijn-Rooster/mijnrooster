<script>
  import { Button, Input } from "flowbite-svelte";
  import { logIn } from "../stores/UserStore";
  import { navigate } from "../stores/RouterStore";

  let email = "";
  let password = "";

  function handleLogin() {
    // Add your login logic here
    console.log("Email:", email);
    console.log("Password:", password);
    logIn();
    navigate("/");
  }
</script>

<div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
  <h2 class="text-2xl font-bold text-center text-gray-500">Login</h2>
  <form on:submit|preventDefault={handleLogin} class="space-y-4">
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700"
        >Email</label
      >
      <Input id="email" type="email" bind:value={email} required class="mt-1" />
    </div>
    <div>
      <label for="password" class="block text-sm font-medium text-gray-700"
        >Password</label
      >
      <Input
        id="password"
        type="password"
        bind:value={password}
        required
        class="mt-1"
      />
    </div>
    <div>
      <Button type="submit" class="w-full">Login</Button>
    </div>
  </form>
</div>
