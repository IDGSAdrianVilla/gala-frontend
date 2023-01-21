import { Component, OnInit } from '@angular/core';
import { LoginService } from '../services/login.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})

export class LoginComponent implements OnInit {
  
  constructor (
    private loginService : LoginService 
  ){

  }

  ngOnInit(): void {
    this.login();
  }

  login() : any {
    const data = {
<<<<<<< Updated upstream
      correo : 'fabi@gmail.com',
      contrasena : '123456'
    }  
=======
      correo : 'faabi@gmail.com',
      password : '123456'
    }
>>>>>>> Stashed changes
    
    this.loginService.login(data).subscribe(
      respuesta => {
        console.log('respuesta');
        console.log(respuesta);
      },
      error => {
        console.log('error');
        console.log(error);
      }
    )
  }
}
