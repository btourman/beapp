import {
  Admin,
  Resource,
  ListGuesser,
} from "react-admin";
import { dataProvider } from "./dataProvider";
import { authProvider } from "./authProvider";
import {CityCreateOrUpdate} from "./city/CityCreateOrUpdate";
import {StationCreateOrUpdate} from "./station/StationCreateOrUpdate";
import {StationShow} from "./station/StationShow";
import {CityShow} from "./city/CityShow";

export const App = () => (
  <Admin dataProvider={dataProvider} authProvider={authProvider}>
    <Resource name="city" list={<ListGuesser exporter={false}/>} edit={<CityCreateOrUpdate isUpdating={true}/>} show={CityShow} create={CityCreateOrUpdate}/>
    <Resource name="station" list={<ListGuesser exporter={false}/>} edit={<StationCreateOrUpdate isUpdating={true}/>} show={StationShow} create={StationCreateOrUpdate}/>
  </Admin>
);
