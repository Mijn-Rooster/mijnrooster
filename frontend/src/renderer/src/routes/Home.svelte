<script>
  import Versions from "../components/Versions.svelte";
  import electronLogo from "../assets/electron.svg";
  import { navigate } from "../stores/RouterStore";
  import { isLoggedIn } from "../stores/UserStore";
  import { logOut } from "../stores/UserStore";

  console.log($isLoggedIn);

  $: {
    if (!$isLoggedIn) {
      navigate("/login");
    }
  }


  const ipcHandle = () => window.electron.ipcRenderer.send("ping");

  const apiRequest = async () => {
    try {
      const response = await fetch("http://localhost:8000");
      const data = await response.json();
      console.log(data);
      alert(data.message);
    } catch (error) {
      console.error("Error fetching data:", error);
      alert("Error fetching data:", error);
    }
  };
</script>

<img alt="logo" class="logo" src={electronLogo} />
<div class="creator">Powered by electron-vite</div>
<div class="text">
  Build an Electron app with
  <span class="svelte">Svelte</span>
</div>
<p class="tip">Please try pressing <code>F12</code> to open the devTool</p>
<div class="actions">
  <div class="action">
    <a href="https://electron-vite.org/" target="_blank" rel="noreferrer"
      >Documentation</a
    >
  </div>
  <div class="action">
    <!-- svelte-ignore a11y-click-events-have-key-events a11y-no-static-element-interactions a11y-missing-attribute-->
    <a target="_blank" rel="noreferrer" on:click={ipcHandle}>Send IPC</a>
  </div>
  <div class="action">
    <!-- svelte-ignore a11y-click-events-have-key-events a11y-no-static-element-interactions a11y-missing-attribute-->
    <a target="_blank" rel="noreferrer" on:click={apiRequest}
      >API call to backend</a
    >
  </div>
  <div class="action">
    <!-- svelte-ignore a11y-click-events-have-key-events a11y-no-static-element-interactions a11y-missing-attribute-->
    <a target="_blank" rel="noreferrer" on:click={() => navigate("/test")}
      >Go to test page</a
    >
  </div>
  <div class="action">
    <!-- svelte-ignore a11y-click-events-have-key-events a11y-no-static-element-interactions a11y-missing-attribute-->
    <a target="_blank" rel="noreferrer" on:click={() => logOut()}
      >Logout</a
    >
    </div> 
</div>
<Versions />
