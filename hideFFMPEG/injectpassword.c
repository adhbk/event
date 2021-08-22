#define _GNU_SOURCE
#include <dlfcn.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
/*
 *  Programme permettant de cacher le dernier argument donné à une commande
 *  et de le remplacer par le contenu du fichier défini par la macro NOM_FICHIER
 * 
 *  Pour compiler utilisez la commande:
 *  gcc -O2 -fPIC -shared -o injectpassword.so injectpassword.c -ldl
 *  
 *  Pour utiliser:
 *  LD_PRELOAD=$PWD/injectpassword.so Commande [args] ArgumentCaché
 * 
 *  Exemple d'utilisation:
 *  LD_PRELOAD=$PWD/injectpassword.so ffmpeg -rtsp_transport tcp -vcodec libx264 -acodec aac outputtest.mp4 -i ip 
 */

#define NOM_FICHIER "urlVersCamera\0"
#define TAILLE_NOM 13

int replacechar(char *str, char orig, char rep) {
    char *ix = str;
    int n = 0;
    while((ix = strchr(ix, orig)) != NULL) {
        *ix++ = rep;
        n++;
    }
    return n;
}

int __libc_start_main(
    int (*main) (int, char * *, char * *),
    int argc, char * * ubp_av,
    void (*init) (void),
    void (*fini) (void),
    void (*rtld_fini) (void),
    void (* stack_end)
  )
{
  int (*next)(
    int (*main) (int, char * *, char * *),
    int argc, char * * ubp_av,
    void (*init) (void),
    void (*fini) (void),
    void (*rtld_fini) (void),
    void (* stack_end)
  ) = dlsym(RTLD_NEXT, "__libc_start_main");
    FILE * fp;
    char * line = NULL;
    size_t len = 0;
    ssize_t read;
    
    const char* name = NOM_FICHIER;
    const char* extension = ubp_av[argc - 1];

    char* name_with_extension;
    name_with_extension = malloc(strlen(name)+1+strlen(extension)); /* make space for the new string (should check the return value ...) */
    strcpy(name_with_extension, name); /* copy name into the new var */
    strcat(name_with_extension, extension); /* add the extension */

    //Ouverture du fichier
    fp = fopen(name_with_extension, "r");

    if (fp == NULL)
        exit(EXIT_FAILURE);
    //Lecture ligne par ligne
    while ((read = getline(&line, &len, fp)) != -1) {
        //Remplace le dernier argument par le contenu du fichier
        strcpy(ubp_av[argc - 1], line);
        //Enlève le '\n' de fin de ligne
        replacechar(ubp_av[argc - 2],'\n','\0');
    }
    //Fermeture du fichier
    fclose(fp);
    //Libération de la ligne #Pas de fuite mémoire
    if (line)
        free(line);
    if(name_with_extension)
      free(name_with_extension);

  return next(main, argc, ubp_av, init, fini, rtld_fini, stack_end);
}