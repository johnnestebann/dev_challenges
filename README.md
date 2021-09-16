## Planning Poker

### Estructura
#### Intro
Este desarrollo está realizado con la última versión de Symfony a la fecha (v5.3.7) siguiendo una Arquitectura Hexagonal,
respetando los patrones SOLID, gestionando los errores con excepciones de dominio, utilizando las nuevas características
de PHP 8 (named arguments, union types y match expressions), versionando los endpoints, utilizando Redis como persistencia,
con tests unitarios y de integración.

#### Flujo de petición
- En la capa de infraestructura se encuentran los puertos `(Workana\Infrastructure\Delivery\Action\V1\Issue)` que mapean las
peticiones http configuradas en el archivo de configuraciones de las rutas de Symfony (`config/routes/v1/issue.yaml`).
Cada puerto es una clase con su responsabilidad única de responder a esa acción, llamando a los servicios/colaboradores
necesarios para resolver la solicitud por inyección de dependencias.
También existen en esta capa: servicios de response para los puertos http, el servicio de conexión a Redis y el adaptador
a Redis para persistencia (Repositorio)
- En la capa de aplicación (`Workana\Application\Service\V1\Issue`) se encuentran los servicios/colaboradores que gestionan
al dominio para resolver sus funciones únicas. Nótese que se utiliza inversión de dependencia para inyectar una interfaz
de dominio que está implementada en infraestructura. De esta manera, sin conocer infraestructura, aplicación puede utilizar
una implementación de infraestructura definida en un contrato de dominio, permitiendo un alto desacople de infraestructura.
También se encuentran las validaciones que disparan las excepciones de dominio.
- En la capa de dominio (`Workana\Domain\Model\Issue`) tenemos la lógica de negocio, las entidades y su comportamiento.
También existen los contratos que se implementarán en infraestructura y las excepciones de dominio. Se utiliza named
constructors.

#### Tests
Los tests utilizan Object Mother para instanciar los modelos de prueba en `Workana\Tests\Domain\Model\Issue`. Los test
unitarios en la capa de aplicación (`Workana\Tests\Application\Service\V1\Issue`) se encargan de testear los servicios de
aplicación con Dobles de tests, moqueando infraestructura. Los test de integración (también podrían ser los de aceptación)
en la capa de infraestructura `Workana\Tests\Infrastructure\Delivery\Action\V1\Issue` validan el funcionamiento del
servicio de persistencia en infraestructura en conjunto con los servicios de aplicación.

### Get up and running

To run this code you need:
  - [Docker](https://www.docker.com/get-started) and [docker-compose](https://docs.docker.com/compose/install/) installed

Then:
  - Clone this repo: `git clone https://github.com/johnnestebann/dev_challenges.git`.
  - Run `make up`.
  
Check if services are up and running:
  - PHP backend in [http://localhost/api/v1/issue/1](http://localhost/api/v1/issue/1)


### Endpoints

##### `POST /api/v1/issue/{:issue}/join` - Used to join `{:issue}`. 
   - Must receive a payload with the intended name. ie: `{"name": "florencia"}`
   - If issue not exists generate a new one.
 
##### `POST /api/v1/issue/{:issue}/vote` - Used to vote `{:issue}`.
   - Must receive a payload with the vote value. `{"name": "florencia", "vote": 12}`
   - Send a -1 vote value to mark issue as passed `{"name": "florencia", "vote": -1}`
   - Reject votes when status of `{:issue}` is not `voting`. 
   - Reject votes if user not joined `{:issue}`. 
   - Reject votes if user already `voted` or `passed`.
  
##### `GET /api/v1/issue/{:issue}` - Returns the status of issue
   During `voting` the values votes are hidden until all members voted.
   - Issue is `voting`: 
        ````json
        {
            "status": "voting", 
            "members": {
                "John": {
                    "status": "voted",
                    "value": 0
                },
                "Paul": {
                    "status": "waiting",
                    "value": 0
                }
            },
            "avg": 0
         }
        ````
   - Issue is `reveal` when all users emitted their votes: 
        ````json
        {
            "status": "reveal", 
            "members": {
                "John": {
                    "status": "voted",
                    "value": 12
                },
                "Paul": {
                    "status": "voted",
                    "value": 10
                }
            },
            "avg": 11
         }
        ````

### Query with CURL
- #### Join Member 'Fernando' to Issue #1
  - `curl -d '{"name":"Fernando"}' -H 'Content-Type: application/json' http://localhost/api/v1/issue/1/join`
- #### Member Fernando vote 12 on Issue #1
  - `curl -d '{"name":"Fernando", "vote": 12}' -H 'Content-Type: application/json' http://localhost/api/v1/issue/1/vote`
- #### Member Fernando passed on Issue #1
  - `curl -d '{"name":"Fernando", "vote": -1}' -H 'Content-Type: application/json' http://localhost/api/v1/issue/1/vote`
- #### Get Issue #1 status
  - `curl http://localhost/api/v1/issue/1`

### Tools
- #### PHPUnit
  - Run `make test` to execute all the battery of unit and integration tests using PHPUnit.
- #### PHPStan
  - Run `make stan` to execute a static analysis of every class using PHPStan.
- #### Sniff
  - Run `make sniff` to execute PHPCBF to correct coding standard violations.