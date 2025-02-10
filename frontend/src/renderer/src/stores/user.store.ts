import { writable } from "svelte/store";

export interface User {
  fullName: string;
  isLoggedIn: boolean;
  leerlingnummer: string;
  schoolInSchoolYear: string;
  firstName: string;
  prefix: string;
  lastName: string;
  code: string;
}

export const user = writable<User>({
  fullName: "",
  isLoggedIn: false,
  leerlingnummer: "",
  schoolInSchoolYear: "",
  firstName: "",
  prefix: "",
  lastName: "",
  code: "",
});
