import { HomeComponent } from './home.component';
import { InicioComponent } from './modules/inicio/inicio.component';
import { Routes } from '@angular/router';
import { AdminGuard } from '../../guards/admin.guard';
import { UsuariosRegistroComponent } from './modules/usuarios/usuarios-registro/usuarios-registro.component';
import { UsuariosConsultaComponent } from './modules/usuarios/usuarios-consulta/usuarios-consulta.component';
import { UsuariosModificacionComponent } from './modules/usuarios/usuarios-modificacion/usuarios-modificacion.component';
import { ClientesRegistroComponent } from './modules/clientes/clientes-registro/clientes-registro.component';
import { ClientesConsultaComponent } from './modules/clientes/clientes-consulta/clientes-consulta.component';
import { ClientesModificacionComponent } from './modules/clientes/clientes-modificacion/clientes-modificacion.component';
import { PoblacionesComponent } from './modules/catalogos/poblaciones/poblaciones.component';
import { ProblemasComponent } from './modules/catalogos/problemas/problemas.component';
import { TipoInstalacionesComponent } from './modules/catalogos/tipo-instalaciones/tipo-instalaciones.component';
import { RolesComponent } from './modules/catalogos/roles/roles.component';

export const HomeRoutes: Routes = [
  {
    path : 'gala',
    canActivate : [AdminGuard],
    component: HomeComponent,
    children: [
      {
        path : 'inicio',
        component: InicioComponent
      },
      {
        path : 'usuarios/registro',
        component: UsuariosRegistroComponent
      },
      {
        path : 'usuarios',
        component : UsuariosConsultaComponent
      },
      {
        path : 'usuarios/modificacion/:pkusuario',
        component: UsuariosModificacionComponent
      },
      {
        path : 'clientes/registro',
        component: ClientesRegistroComponent
      },
      {
        path : 'clientes',
        component: ClientesConsultaComponent
      },
      {
        path : 'clientes/modificacion/:pkcliente',
        component: ClientesModificacionComponent
      },
      {
        path : 'catalogos/poblaciones',
        component: PoblacionesComponent
      },{
        path : 'catalogos/problemas',
        component: ProblemasComponent
      },
      {
        path : 'catalogos/tipo-instalaciones',
        component: TipoInstalacionesComponent
      },{
        path: 'catalogos/roles',
        component: RolesComponent
      }
    ]
  }
]
