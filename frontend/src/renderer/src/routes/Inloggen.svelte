<script lang="ts">
   import MenuBar from "../components/MenuBar.svelte";
   import { navigate } from "../stores/RouterStore";
    import { Button, Footer, Label, Input, Modal } from 'flowbite-svelte';
    import { ArrowLeftOutline, UserSolid, ExclamationCircleOutline  } from 'flowbite-svelte-icons';
    import { user } from "../stores/UserStore"; // Import the user store
    import { retrieveUserInfo } from "../services/api.service"; // Import the retrieveUserInfo function
    
    let popupModal = false;

    let leerlingnummer = '';
  let schoolInSchoolYear = '1001702'; // Updated value

  async function handleSubmit(event) {
    event.preventDefault();
    
    // Save the leerlingnummer in the user store
    user.update(current => ({ ...current, leerlingnummer }));

    try {
      const userInfo = await retrieveUserInfo(schoolInSchoolYear, leerlingnummer);
      if (!userInfo) {
        throw new Error('User info fetch failed');
      }
      // Store the data for later use
      user.set(userInfo);
      // Navigate to another page after login
      navigate('/dashboard'); // Change to your desired route
    } catch (error) {
      // Show a pop-up screen with the error message
      popupModal = true;
    }
  }
</script>

<MenuBar />

<main>
<div class="my-5 ">
  <div class="w-full max-w-md p-4 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800 my-1">
    <h2 class="text-3xl font-semibold text-center text-gray-800 dark:text-white">Inloggen</h2>
    <form class="mt-6" on:submit={handleSubmit}>
      <div>
        <Label for="leerlingnummer" class="block text-sm text-gray-800 dark:text-gray-200">leerlingnummer</Label>
        <Input type="text" id="leerlingnummer" bind:value={leerlingnummer} placeholder="leerlingnummer" class="w-full px-4 py-2 mt-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:border-primary-400 focus:outline-none focus:ring dark:text-gray-300"> 
          <UserSolid slot="left" class="w-4 h-4" />
        </Input>
      </div>

      <div class="mt-6">
        <Button type="submit" class="w-full py-2 text-white bg-primary-500 rounded-md hover:bg-primary-600 focus:outline-none focus:ring">Bekijk je rooster!</Button>
      </div>
    </form>
  </div>
</div>
</main>

<Modal bind:open={popupModal} size="xs" autoclose>
  <div class="text-center">
    <ExclamationCircleOutline class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" />
    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">It seems that your username is incorrect. Please fill in your correct username.</h3>
    <Button style="background-color: #291c5b" class="me-2 text-white" on:click={() => { popupModal = false; leerlingnummer = '';} }>Ok</Button>
    
  </div>
</Modal>

<Footer class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
<Button class="gap-2 px-2" on:click={() => navigate("/")}><ArrowLeftOutline/>Terug</Button>
</Footer>

