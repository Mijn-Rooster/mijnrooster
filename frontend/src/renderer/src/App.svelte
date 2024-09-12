<script>
  import { Router, Route } from "svelte-routing";
  import Home from "./routes/Home.svelte";
  import Test from "./routes/Test.svelte";

  export let url = "";
</script>

<Router {url}>
    <Route path="/" component="{Home}" />
    <Route path="/test" component="{Test}" />
</Router>
